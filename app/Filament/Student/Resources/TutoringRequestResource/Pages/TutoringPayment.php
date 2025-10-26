<?php

namespace App\Filament\Student\Resources\TutoringRequestResource\Pages;

use App\Filament\Student\Resources\TutoringRequestResource;
use App\Models\TutoringRequest;
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
            // TODO: Integrate with M-Pesa STK Push API
            // Example: $mpesa->STKPush($this->phoneNumber, $amount, ...)
            
            // Simulate API call
            sleep(2);
            
            $this->record->update([
                'status' => 'pending', // Keep as pending for old schema
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Payment Successful! 🎉')
                ->success()
                ->body('Your tutoring request has been submitted. Admin will assign a tutor shortly.')
                ->duration(5000)
                ->send();
            
            $this->processing = false;
            
            return redirect()->to(route('filament.student.resources.tutoring-requests.index'));
            
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
            // TODO: Integrate with PayPal API
            // Example: return redirect($paypal->getApprovalUrl());
            
            sleep(1);
            
            $this->record->update([
                'status' => 'pending',
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Payment Successful! 🎉')
                ->success()
                ->body('Your tutoring request has been submitted. Admin will assign a tutor shortly.')
                ->duration(5000)
                ->send();
            
            return redirect()->to(route('filament.student.resources.tutoring-requests.index'));
            
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
            // TODO: Integrate with Pesapal API
            // Example: return redirect($pesapal->getIframeUrl());
            
            sleep(1);
            
            $this->record->update([
                'status' => 'pending',
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Payment Successful! 🎉')
                ->success()
                ->body('Your tutoring request has been submitted. Admin will assign a tutor shortly.')
                ->duration(5000)
                ->send();
            
            return redirect()->to(route('filament.student.resources.tutoring-requests.index'));
            
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
