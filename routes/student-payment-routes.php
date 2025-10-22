<?php

use App\Http\Controllers\ProjectPaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Student Payment Routes
|--------------------------------------------------------------------------
|
| Routes for handling project payments by students
|
*/

Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    // Payment page
    Route::get('/projects/{project}/payment', [ProjectPaymentController::class, 'show'])
        ->name('project.payment');
    
    // Payment processing
    Route::post('/projects/{project}/payment/mpesa', [ProjectPaymentController::class, 'processMpesa'])
        ->name('project.payment.mpesa');
    
    Route::post('/projects/{project}/payment/paypal', [ProjectPaymentController::class, 'processPaypal'])
        ->name('project.payment.paypal');
    
    Route::post('/projects/{project}/payment/pesapal', [ProjectPaymentController::class, 'processPesapal'])
        ->name('project.payment.pesapal');
    
    // Payment callbacks
    Route::get('/projects/{project}/payment/success', [ProjectPaymentController::class, 'success'])
        ->name('project.payment.success');
    
    Route::get('/projects/{project}/payment/cancel', [ProjectPaymentController::class, 'cancel'])
        ->name('project.payment.cancel');
});
