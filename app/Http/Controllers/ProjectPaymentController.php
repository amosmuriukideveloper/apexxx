<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectPaymentController extends Controller
{
    public function show(Project $project)
    {
        // Verify the project belongs to the authenticated user
        if ($project->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this project.');
        }

        // Check if already paid
        if ($project->payment_status === 'paid') {
            return redirect()->route('student.projects.index')
                ->with('info', 'This project has already been paid for.');
        }

        // Get active payment methods
        $mpesa = PaymentSetting::where('provider', 'mpesa')
            ->where('is_active', true)
            ->first();
            
        $paypal = PaymentSetting::where('provider', 'paypal')
            ->where('is_active', true)
            ->first();
            
        $pesapal = PaymentSetting::where('provider', 'pesapal')
            ->where('is_active', true)
            ->first();

        return view('student.payment', compact('project', 'mpesa', 'paypal', 'pesapal'));
    }

    public function processMpesa(Request $request, Project $project)
    {
        // Implement M-Pesa STK Push
        $mpesa = PaymentSetting::where('provider', 'mpesa')->first();
        
        if (!$mpesa || !$mpesa->is_active) {
            return back()->with('error', 'M-Pesa payment is not available.');
        }

        // TODO: Implement actual M-Pesa integration
        // This is a placeholder for the M-Pesa payment flow
        
        return view('student.payment.mpesa', compact('project', 'mpesa'));
    }

    public function processPaypal(Request $request, Project $project)
    {
        // Implement PayPal checkout
        $paypal = PaymentSetting::where('provider', 'paypal')->first();
        
        if (!$paypal || !$paypal->is_active) {
            return back()->with('error', 'PayPal payment is not available.');
        }

        // TODO: Implement actual PayPal integration
        // This is a placeholder for the PayPal payment flow
        
        return redirect()->away('https://www.paypal.com/checkoutnow?token=...');
    }

    public function processPesapal(Request $request, Project $project)
    {
        // Implement PesaPal checkout
        $pesapal = PaymentSetting::where('provider', 'pesapal')->first();
        
        if (!$pesapal || !$pesapal->is_active) {
            return back()->with('error', 'PesaPal payment is not available.');
        }

        // TODO: Implement actual PesaPal integration
        // This is a placeholder for the PesaPal payment flow
        
        return redirect()->away('https://www.pesapal.com/...');
    }

    public function success(Request $request, Project $project)
    {
        // Handle successful payment
        $project->update([
            'payment_status' => 'paid',
        ]);

        return redirect()->route('student.projects.show', $project)
            ->with('success', 'Payment successful! Your project has been submitted for assignment.');
    }

    public function cancel(Request $request, Project $project)
    {
        // Handle cancelled payment
        return redirect()->route('student.project.payment', $project)
            ->with('warning', 'Payment was cancelled. Please try again.');
    }
}
