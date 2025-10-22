<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->string('user_type'); // User, Expert, Tutor, ContentCreator
            $table->unsignedBigInteger('user_id');
            $table->enum('transaction_type', ['payment', 'payout', 'refund', 'commission']);
            $table->string('service_type')->nullable(); // Project, TutoringSession, Course
            $table->unsignedBigInteger('service_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->decimal('platform_commission', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->enum('payment_method', ['mpesa', 'paypal', 'pesapal', 'bank_transfer', 'wallet']);
            $table->string('payment_gateway_ref')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('payment_phone')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_type', 'user_id']);
            $table->index(['service_type', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
