<?php

namespace App\Filament\Student\Resources\CourseResource\Pages;

use App\Filament\Student\Resources\CourseResource;
use App\Models\Course;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class CoursePayment extends Page
{
    protected static string $resource = CourseResource::class;
    protected static string $view = 'filament.student.pages.course-payment';
    
    public Course $record;
    public $selectedMethod = null;
    public $phoneNumber = null;
    public $processing = false;
    
    public function mount(): void
    {
        // Allow anyone to purchase a course
        // No status check needed for courses
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
            // Example: $mpesa->STKPush($this->phoneNumber, $this->record->price, ...)
            
            // Simulate API call
            sleep(2);
            
            // Create enrollment
            $this->record->enrollments()->create([
                'student_id' => Auth::id(),
                'enrolled_at' => now(),
                'progress' => 0,
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Payment Successful! 🎉')
                ->success()
                ->body('You are now enrolled in this course. Start learning!')
                ->duration(5000)
                ->send();
            
            $this->processing = false;
            
            return redirect()->to(route('filament.student.resources.my-courses.index'));
            
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
            
            // Create enrollment
            $this->record->enrollments()->create([
                'student_id' => Auth::id(),
                'enrolled_at' => now(),
                'progress' => 0,
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Payment Successful! 🎉')
                ->success()
                ->body('You are now enrolled in this course. Start learning!')
                ->duration(5000)
                ->send();
            
            return redirect()->to(route('filament.student.resources.my-courses.index'));
            
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
            
            // Create enrollment
            $this->record->enrollments()->create([
                'student_id' => Auth::id(),
                'enrolled_at' => now(),
                'progress' => 0,
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Payment Successful! 🎉')
                ->success()
                ->body('You are now enrolled in this course. Start learning!')
                ->duration(5000)
                ->send();
            
            return redirect()->to(route('filament.student.resources.my-courses.index'));
            
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
