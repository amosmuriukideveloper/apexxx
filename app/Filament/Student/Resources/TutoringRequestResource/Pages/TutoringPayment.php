<?php

namespace App\Filament\Student\Resources\TutoringRequestResource\Pages;

use App\Filament\Student\Resources\TutoringRequestResource;
use App\Models\TutoringRequest;
use App\Services\MpesaService;
use App\Services\PayPalService;
use App\Services\PesapalService;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class TutoringPayment extends Page
{
    protected static string $resource = TutoringRequestResource::class;
    protected static string $view = 'filament.student.pages.tutoring-payment';
    
    public TutoringRequest $record;
    public $selectedMethod = null;
    public $phoneNumber = null;
    public $processing = false;
    
    public function mount(): void
    {
        if ($this->record->student_id !== Auth::id()) {
            abort(403);
        }
        
        // Old schema uses 'pending' instead of 'pending_payment'
        if (!in_array($this->record->status, ['pending', 'pending_payment'])) {
            abort(403, 'Payment already completed or not required');
        }
    }
    
    public function selectPaymentMethod($method)
    {
        $this->selectedMethod = $method;
    }
    
    public function processMpesaPayment()
    {
        $this->validate([
            'phoneNumber' => ['required', 'regex:/^254[0-9]{9}$/'],
        ], [
            'phoneNumber.required' => 'Phone number is required',
            'phoneNumber.regex' => 'Phone number must be in format 254XXXXXXXXX',
        ]);
        
        $this->processing = true;
        
        try {
            $mpesaService = new MpesaService();
            
            // Get the amount to charge
            $amount = $this->record->total_price ?? $this->record->base_price ?? 50;
            
            if ($amount <= 0) {
                throw new \Exception('Invalid payment amount.');
            }
            
            $result = $mpesaService->initiateSTKPush(
                $this->phoneNumber,
                $amount,
                $this->record->request_number,
                'Payment for Tutoring Request #' . $this->record->request_number
            );
            
            if ($result['success']) {
                \Filament\Notifications\Notification::make()
                    ->title('STK Push Sent! 📱')
                    ->success()
                    ->body($result['message'] . ' Please check your phone and enter your M-Pesa PIN.')
                    ->duration(10000)
                    ->send();
                
                $this->processing = false;
                
                // Store checkout request ID
                session(['mpesa_checkout_request_id' => $result['checkout_request_id']]);
                
                // Mark as paid (in production, verify via callback)
                $this->record->update([
                    'status' => 'pending',
                    'paid_at' => now(),
                ]);
                
                return redirect()->to(route('filament.student.resources.tutoring-requests.index'));
            } else {
                throw new \Exception($result['message']);
            }
            
        } catch (\Exception $e) {
            $this->processing = false;
            
            \Filament\Notifications\Notification::make()
                ->title('Payment Failed ❌')
                ->danger()
                ->body('Error: ' . $e->getMessage() . ' Please try again.')
                ->persistent()
                ->send();
        }
    }
    
    public function processPayPalPayment()
    {
        try {
            $paypalService = new PayPalService();
            
            // Get the amount to charge
            $amount = $this->record->total_price ?? $this->record->base_price ?? 50;
            
            if ($amount <= 0) {
                throw new \Exception('Invalid payment amount.');
            }
            
            $returnUrl = route('filament.student.resources.tutoring-requests.index') . '?paypal=success&request=' . $this->record->id;
            $cancelUrl = route('filament.student.resources.tutoring-requests.payment', ['record' => $this->record->id]) . '?paypal=cancelled';
            
            $result = $paypalService->createOrder(
                $amount,
                'USD',
                $this->record->request_number,
                $returnUrl,
                $cancelUrl
            );
            
            if ($result['success'] && isset($result['approval_url'])) {
                session(['paypal_order_id' => $result['order_id']]);
                return redirect()->away($result['approval_url']);
            } else {
                throw new \Exception($result['message'] ?? 'Failed to create PayPal order');
            }
            
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title('Payment Failed ❌')
                ->danger()
                ->body('Error: ' . $e->getMessage())
                ->persistent()
                ->send();
        }
    }
    
    public function processPesapalPayment()
    {
        try {
            $pesapalService = new PesapalService();
            
            // Get the amount to charge
            $amount = $this->record->total_price ?? $this->record->base_price ?? 50;
            
            if ($amount <= 0) {
                throw new \Exception('Invalid payment amount.');
            }
            
            $callbackUrl = route('filament.student.resources.tutoring-requests.index') . '?pesapal=callback&request=' . $this->record->id;
            
            $customerDetails = [
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? '',
                'first_name' => Auth::user()->name ?? 'Customer',
                'last_name' => '',
            ];
            
            $result = $pesapalService->submitOrderRequest(
                $amount,
                'KES',
                $this->record->request_number,
                'Payment for Tutoring Request #' . $this->record->request_number,
                $callbackUrl,
                $customerDetails
            );
            
            if ($result['success'] && isset($result['redirect_url'])) {
                session(['pesapal_order_tracking_id' => $result['order_tracking_id']]);
                return redirect()->away($result['redirect_url']);
            } else {
                throw new \Exception($result['message'] ?? 'Failed to create Pesapal order');
            }
            
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title('Payment Failed ❌')
                ->danger()
                ->body('Error: ' . $e->getMessage())
                ->persistent()
                ->send();
        }
    }
}
