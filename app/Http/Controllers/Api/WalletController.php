<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Display user's wallet information
     */
    public function show()
    {
        $user = Auth::user();
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'balance' => 0.00,
                'currency' => 'USD',
                'is_active' => true,
            ]
        );

        return response()->json([
            'wallet' => $wallet,
            'recent_transactions' => $wallet->transactions()
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }

    /**
     * Get wallet transactions
     */
    public function transactions(Request $request)
    {
        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return response()->json(['transactions' => []], 200);
        }

        $query = $wallet->transactions();

        // Apply filters
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(20);

        return response()->json($transactions);
    }

    /**
     * Deposit funds to wallet
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:10000',
            'payment_method' => 'required|in:mpesa,paypal,bank_transfer,credit_card',
            'phone_number' => 'required_if:payment_method,mpesa|string',
        ]);

        $user = Auth::user();
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'balance' => 0.00,
                'currency' => 'USD',
                'is_active' => true,
            ]
        );

        DB::beginTransaction();

        try {
            // Create pending transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'deposit',
                'amount' => $request->amount,
                'balance_after' => $wallet->balance, // Will be updated when completed
                'currency' => 'USD',
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'reference_id' => 'DEP_' . time() . '_' . $user->id,
                'description' => "Wallet deposit via {$request->payment_method}",
                'metadata' => [
                    'phone_number' => $request->phone_number ?? null,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);

            // Process payment based on method
            $paymentResult = $this->processPayment($transaction, $request);

            if ($paymentResult['success']) {
                // Update transaction status
                $transaction->update([
                    'status' => 'completed',
                    'balance_after' => $wallet->balance + $request->amount,
                    'processed_at' => now(),
                ]);

                // Update wallet balance
                $wallet->increment('balance', $request->amount);
                $wallet->update(['last_transaction_at' => now()]);

                DB::commit();

                return response()->json([
                    'message' => 'Deposit successful',
                    'transaction' => $transaction->fresh(),
                    'wallet' => $wallet->fresh(),
                ]);
            } else {
                $transaction->update([
                    'status' => 'failed',
                    'failure_reason' => $paymentResult['message'],
                ]);

                DB::commit();

                return response()->json([
                    'message' => 'Deposit failed: ' . $paymentResult['message'],
                    'transaction' => $transaction->fresh(),
                ], 400);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Deposit failed due to system error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Withdraw funds from wallet
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'withdrawal_method' => 'required|in:mpesa,paypal,bank_transfer',
            'account_details' => 'required|array',
        ]);

        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet || $wallet->balance < $request->amount) {
            return response()->json([
                'message' => 'Insufficient funds'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Create pending transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'withdrawal',
                'amount' => $request->amount,
                'balance_after' => $wallet->balance - $request->amount,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_method' => $request->withdrawal_method,
                'reference_id' => 'WTH_' . time() . '_' . $user->id,
                'description' => "Wallet withdrawal via {$request->withdrawal_method}",
                'metadata' => [
                    'account_details' => $request->account_details,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);

            // Deduct from wallet immediately (will be reversed if withdrawal fails)
            $wallet->decrement('balance', $request->amount);
            $wallet->update(['last_transaction_at' => now()]);

            // Process withdrawal (this would typically be queued)
            $withdrawalResult = $this->processWithdrawal($transaction, $request);

            if ($withdrawalResult['success']) {
                $transaction->update([
                    'status' => 'completed',
                    'processed_at' => now(),
                ]);
            } else {
                // Reverse the deduction
                $wallet->increment('balance', $request->amount);
                $transaction->update([
                    'status' => 'failed',
                    'balance_after' => $wallet->balance,
                    'failure_reason' => $withdrawalResult['message'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => $withdrawalResult['success'] ? 'Withdrawal successful' : 'Withdrawal failed: ' . $withdrawalResult['message'],
                'transaction' => $transaction->fresh(),
                'wallet' => $wallet->fresh(),
            ], $withdrawalResult['success'] ? 200 : 400);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Withdrawal failed due to system error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process payment (mock implementation)
     */
    private function processPayment(Transaction $transaction, Request $request): array
    {
        // This is a mock implementation
        // In a real application, you would integrate with actual payment gateways
        
        switch ($request->payment_method) {
            case 'mpesa':
                // Integrate with M-Pesa API
                return ['success' => true, 'message' => 'M-Pesa payment processed'];
                
            case 'paypal':
                // Integrate with PayPal API
                return ['success' => true, 'message' => 'PayPal payment processed'];
                
            case 'bank_transfer':
                // Handle bank transfer
                return ['success' => true, 'message' => 'Bank transfer initiated'];
                
            case 'credit_card':
                // Integrate with credit card processor
                return ['success' => true, 'message' => 'Credit card payment processed'];
                
            default:
                return ['success' => false, 'message' => 'Unsupported payment method'];
        }
    }

    /**
     * Process withdrawal (mock implementation)
     */
    private function processWithdrawal(Transaction $transaction, Request $request): array
    {
        // This is a mock implementation
        // In a real application, you would integrate with actual payment gateways
        
        switch ($request->withdrawal_method) {
            case 'mpesa':
                // Integrate with M-Pesa API for withdrawal
                return ['success' => true, 'message' => 'M-Pesa withdrawal processed'];
                
            case 'paypal':
                // Integrate with PayPal API for withdrawal
                return ['success' => true, 'message' => 'PayPal withdrawal processed'];
                
            case 'bank_transfer':
                // Handle bank transfer withdrawal
                return ['success' => true, 'message' => 'Bank transfer withdrawal initiated'];
                
            default:
                return ['success' => false, 'message' => 'Unsupported withdrawal method'];
        }
    }
}
