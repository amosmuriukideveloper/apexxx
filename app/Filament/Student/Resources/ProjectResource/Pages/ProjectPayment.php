<?php

namespace App\Filament\Student\Resources\ProjectResource\Pages;

use App\Filament\Student\Resources\ProjectResource;
use App\Models\Project;
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
            // TODO: Integrate with M-Pesa STK Push API
            // Example integration:
            // $mpesa = new \Safaricom\Mpesa\Mpesa();
            // $response = $mpesa->STKPush($this->phoneNumber, $this->record->budget, ...);
            
            // For now, simulate successful payment
            sleep(2); // Simulate API call
            
            $this->record->update([
                'status' => 'assigned',
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Payment Successful! 🎉')
                ->success()
                ->body('Your project has been submitted and will be assigned to an expert shortly.')
                ->duration(5000)
                ->send();
            
            $this->processing = false;
            
            return redirect()->to(route('filament.student.resources.projects.index'));
            
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
            // TODO: Integrate with PayPal API
            // Example:
            // $paypal = new \PayPal\Api\Payment();
            // $approvalUrl = $paypal->create(...)->getApprovalLink();
            // return redirect($approvalUrl);
            
            // For now, simulate successful payment
            sleep(1);
            
            $this->record->update([
                'status' => 'assigned',
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Payment Successful! 🎉')
                ->success()
                ->body('Your project has been submitted and will be assigned to an expert shortly.')
                ->duration(5000)
                ->send();
            
            return redirect()->to(route('filament.student.resources.projects.index'));
            
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
            // Example:
            // $pesapal = new \Pesapal\Pesapal();
            // $iframeUrl = $pesapal->PostPesapalDirectOrderV4(...);
            // return redirect($iframeUrl);
            
            // For now, simulate successful payment
            sleep(1);
            
            $this->record->update([
                'status' => 'assigned',
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Payment Successful! 🎉')
                ->success()
                ->body('Your project has been submitted and will be assigned to an expert shortly.')
                ->duration(5000)
                ->send();
            
            return redirect()->to(route('filament.student.resources.projects.index'));
            
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
