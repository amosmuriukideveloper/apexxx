<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Process a payment
     */
    public function process(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:mpesa,paypal,pesapal,credit_card,wallet',
            'description' => 'required|string',
            'metadata' => 'nullable|array',
        ]);

        $user = auth()->user();
        
        DB::beginTransaction();
        
        try {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'payment',
                'amount' => $request->amount,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'reference_id' => 'PAY_' . time() . '_' . $user->id,
                'description' => $request->description,
                'metadata' => array_merge($request->metadata ?? [], [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]),
            ]);

            // Process payment based on method
            $result = $this->processPaymentMethod($transaction, $request);
            
            DB::commit();
            
            return response()->json([
                'transaction' => $transaction,
                'payment_url' => $result['payment_url'] ?? null,
                'message' => $result['message'] ?? 'Payment initiated',
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment processing failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);
            
            return response()->json([
                'message' => 'Payment processing failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get payment status
     */
    public function status(Request $request, $paymentId)
    {
        $transaction = Transaction::where('reference_id', $paymentId)
            ->orWhere('id', $paymentId)
            ->first();

        if (!$transaction) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Check if user can access this transaction
        if (auth()->user()->id !== $transaction->user_id && !auth()->user()->hasRole(['admin', 'super_admin'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'transaction' => $transaction,
            'status' => $transaction->status,
        ]);
    }

    /**
     * Handle M-Pesa webhook
     */
    public function mpesaWebhook(Request $request)
    {
        Log::info('M-Pesa webhook received', $request->all());

        try {
            // Verify M-Pesa signature/authentication here
            
            $data = $request->all();
            $referenceId = $data['TransID'] ?? null;
            
            if (!$referenceId) {
                return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid transaction reference']);
            }

            $transaction = Transaction::where('reference_id', $referenceId)->first();
            
            if (!$transaction) {
                Log::warning('M-Pesa webhook: Transaction not found', ['reference' => $referenceId]);
                return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Transaction not found']);
            }

            DB::beginTransaction();

            if ($data['ResultCode'] == 0) {
                // Payment successful
                $transaction->update([
                    'status' => 'completed',
                    'processed_at' => now(),
                    'metadata' => array_merge($transaction->metadata ?? [], [
                        'mpesa_receipt' => $data['TransID'],
                        'mpesa_response' => $data,
                    ]),
                ]);

                // Update wallet if it's a deposit
                if ($transaction->type === 'deposit') {
                    $wallet = Wallet::find($transaction->wallet_id);
                    if ($wallet) {
                        $wallet->increment('balance', $transaction->amount);
                        $wallet->update(['last_transaction_at' => now()]);
                    }
                }
            } else {
                // Payment failed
                $transaction->update([
                    'status' => 'failed',
                    'failure_reason' => $data['ResultDesc'] ?? 'Payment failed',
                    'metadata' => array_merge($transaction->metadata ?? [], [
                        'mpesa_response' => $data,
                    ]),
                ]);
            }

            DB::commit();

            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('M-Pesa webhook processing failed', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Processing failed']);
        }
    }

    /**
     * Handle PayPal webhook
     */
    public function paypalWebhook(Request $request)
    {
        Log::info('PayPal webhook received', $request->all());

        try {
            // Verify PayPal webhook signature here
            
            $data = $request->all();
            $eventType = $data['event_type'] ?? null;
            
            if ($eventType === 'PAYMENT.CAPTURE.COMPLETED') {
                $paymentId = $data['resource']['id'] ?? null;
                
                $transaction = Transaction::where('metadata->paypal_payment_id', $paymentId)->first();
                
                if ($transaction) {
                    DB::beginTransaction();
                    
                    $transaction->update([
                        'status' => 'completed',
                        'processed_at' => now(),
                        'metadata' => array_merge($transaction->metadata ?? [], [
                            'paypal_response' => $data,
                        ]),
                    ]);

                    // Update wallet if it's a deposit
                    if ($transaction->type === 'deposit') {
                        $wallet = Wallet::find($transaction->wallet_id);
                        if ($wallet) {
                            $wallet->increment('balance', $transaction->amount);
                            $wallet->update(['last_transaction_at' => now()]);
                        }
                    }
                    
                    DB::commit();
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('PayPal webhook processing failed', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Handle PesaPal webhook
     */
    public function pesapalWebhook(Request $request)
    {
        Log::info('PesaPal webhook received', $request->all());

        try {
            // Verify PesaPal IPN signature here
            
            $data = $request->all();
            $referenceId = $data['pesapal_transaction_tracking_id'] ?? null;
            
            if (!$referenceId) {
                return response()->json(['status' => 'invalid reference']);
            }

            $transaction = Transaction::where('reference_id', $referenceId)->first();
            
            if ($transaction) {
                DB::beginTransaction();
                
                $status = $data['pesapal_notification_type'] ?? '';
                
                if ($status === 'COMPLETED') {
                    $transaction->update([
                        'status' => 'completed',
                        'processed_at' => now(),
                        'metadata' => array_merge($transaction->metadata ?? [], [
                            'pesapal_response' => $data,
                        ]),
                    ]);

                    // Update wallet if it's a deposit
                    if ($transaction->type === 'deposit') {
                        $wallet = Wallet::find($transaction->wallet_id);
                        if ($wallet) {
                            $wallet->increment('balance', $transaction->amount);
                            $wallet->update(['last_transaction_at' => now()]);
                        }
                    }
                } else {
                    $transaction->update([
                        'status' => 'failed',
                        'failure_reason' => 'Payment not completed',
                        'metadata' => array_merge($transaction->metadata ?? [], [
                            'pesapal_response' => $data,
                        ]),
                    ]);
                }
                
                DB::commit();
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('PesaPal webhook processing failed', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Process payment based on method
     */
    private function processPaymentMethod(Transaction $transaction, Request $request): array
    {
        switch ($request->payment_method) {
            case 'mpesa':
                return $this->processMpesaPayment($transaction, $request);
                
            case 'paypal':
                return $this->processPaypalPayment($transaction, $request);
                
            case 'pesapal':
                return $this->processPesapalPayment($transaction, $request);
                
            case 'wallet':
                return $this->processWalletPayment($transaction, $request);
                
            default:
                return ['message' => 'Unsupported payment method'];
        }
    }

    /**
     * Process M-Pesa payment
     */
    private function processMpesaPayment(Transaction $transaction, Request $request): array
    {
        // Mock M-Pesa integration
        return [
            'message' => 'M-Pesa payment initiated',
            'payment_url' => null,
        ];
    }

    /**
     * Process PayPal payment
     */
    private function processPaypalPayment(Transaction $transaction, Request $request): array
    {
        // Mock PayPal integration
        return [
            'message' => 'PayPal payment initiated',
            'payment_url' => 'https://paypal.com/checkout/' . $transaction->reference_id,
        ];
    }

    /**
     * Process PesaPal payment
     */
    private function processPesapalPayment(Transaction $transaction, Request $request): array
    {
        // Mock PesaPal integration
        return [
            'message' => 'PesaPal payment initiated',
            'payment_url' => 'https://pesapal.com/checkout/' . $transaction->reference_id,
        ];
    }

    /**
     * Process wallet payment
     */
    private function processWalletPayment(Transaction $transaction, Request $request): array
    {
        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet || $wallet->balance < $transaction->amount) {
            $transaction->update(['status' => 'failed', 'failure_reason' => 'Insufficient wallet balance']);
            return ['message' => 'Insufficient wallet balance'];
        }

        // Deduct from wallet
        $wallet->decrement('balance', $transaction->amount);
        $transaction->update([
            'status' => 'completed',
            'processed_at' => now(),
            'balance_after' => $wallet->balance,
        ]);

        return ['message' => 'Payment completed using wallet'];
    }
}
