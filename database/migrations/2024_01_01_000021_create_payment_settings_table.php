<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('provider', ['mpesa', 'paypal', 'pesapal', 'stripe'])->unique();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_test_mode')->default(true);
            $table->text('credentials'); // Encrypted JSON
            $table->decimal('commission_rate', 5, 2)->default(10.00); // Percentage
            $table->decimal('minimum_payout', 10, 2)->default(50.00);
            $table->enum('payout_schedule', ['weekly', 'bi_weekly', 'monthly'])->default('monthly');
            $table->json('config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
