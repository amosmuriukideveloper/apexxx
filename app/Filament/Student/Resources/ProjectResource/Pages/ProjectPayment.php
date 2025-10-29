<?php

namespace App\Filament\Student\Resources\ProjectResource\Pages;

use App\Filament\Student\Resources\ProjectResource;
use App\Models\Project;
use App\Services\MpesaService;
use App\Services\PayPalService;
use App\Services\PesapalService;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ProjectPayment extends Page
{
    protected static string $resource = ProjectResource::class;
    protected static string $view = 'filament.student.pages.project-payment';
    
    public Project $record;
    public $selectedMethod = null;
    public $phoneNumber = null;
    public $processing = false;
    
    public function mount(): void
    {
        // Verify ownership
        if ($this->record->student_id !== Auth::id()) {
            abort(403);
        }
        
        // Allow payment for pending status (old schema doesn't have payment_status column)
        // Just check that it's not already assigned or completed
        if (in_array($this->record->status, ['assigned', 'in_progress', 'review', 'completed', 'cancelled'])) {
            abort(403, 'Project payment already completed or not available');
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
            $amount = $this->record->budget ?? $this->record->total_price ?? 0;
            
            if ($amount <= 0) {
                throw new \Exception('Invalid payment amount. Please recalculate pricing.');
            }
            
            $result = $mpesaService->initiateSTKPush(
                $this->phoneNumber,
                $amount,
                $this->record->project_number,
                'Payment for Project #' . $this->record->project_number
            );
            
            if ($result['success']) {
                // Update project with payment initiation
                $this->record->update([
                    'payment_status' => 'pending',
                    'status' => 'pending',
                ]);
                
                \Filament\Notifications\Notification::make()
                    ->title('STK Push Sent! 📱')
                    ->success()
                    ->body($result['message'] . ' Please check your phone and enter your M-Pesa PIN.')
                    ->duration(10000)
                    ->send();
                
                $this->processing = false;
                
                // Store checkout request ID for verification
                session(['mpesa_checkout_request_id' => $result['checkout_request_id']]);
                
                // For now, simulate successful payment after STK push
                // In production, you'd verify via callback
                $this->record->update([
                    'payment_status' => 'paid',
                    'status' => 'assigned',
                    'paid_at' => now(),
                ]);
                
                return redirect()->to(route('filament.student.resources.projects.index'));
            } else {
                throw new \Exception($result['message']);
            }
            
        } catch (\Exception $e) {
            $this->processing = false;
            
            \Filament\Notifications\Notification::make()
                ->title('Payment Failed ❌')
                ->danger()
                ->body('Error: ' . $e->getMessage() . ' Please try again or contact support.')
                ->persistent()
                ->send();
        }
    }
    
    public function processPayPalPayment()
    {
        try {
            $paypalService = new PayPalService();
            
            // Get the amount to charge
            $amount = $this->record->budget ?? $this->record->total_price ?? 0;
            
            if ($amount <= 0) {
                throw new \Exception('Invalid payment amount. Please recalculate pricing.');
            }
            
            $returnUrl = route('filament.student.resources.projects.index') . '?paypal=success&project=' . $this->record->id;
            $cancelUrl = route('filament.student.resources.projects.payment', ['record' => $this->record->id]) . '?paypal=cancelled';
            
            $result = $paypalService->createOrder(
                $amount,
                'USD',
                $this->record->project_number,
                $returnUrl,
                $cancelUrl
            );
            
            if ($result['success'] && isset($result['approval_url'])) {
                // Store order ID for verification
                session(['paypal_order_id' => $result['order_id']]);
                
                // Redirect to PayPal
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
            $amount = $this->record->budget ?? $this->record->total_price ?? 0;
            
            if ($amount <= 0) {
                throw new \Exception('Invalid payment amount. Please recalculate pricing.');
            }
            
            $callbackUrl = route('filament.student.resources.projects.index') . '?pesapal=callback&project=' . $this->record->id;
            
            $customerDetails = [
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? '',
                'first_name' => Auth::user()->name ?? 'Customer',
                'last_name' => '',
            ];
            
            $result = $pesapalService->submitOrderRequest(
                $amount,
                'KES',
                $this->record->project_number,
                'Payment for Project #' . $this->record->project_number,
                $callbackUrl,
                $customerDetails
            );
            
            if ($result['success'] && isset($result['redirect_url'])) {
                // Store order tracking ID for verification
                session(['pesapal_order_tracking_id' => $result['order_tracking_id']]);
                
                // Redirect to Pesapal
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
