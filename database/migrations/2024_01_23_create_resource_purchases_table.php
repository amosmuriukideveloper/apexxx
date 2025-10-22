<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->morphs('resource'); // resource_id, resource_type
            $table->decimal('amount_paid', 10, 2);
            $table->string('payment_method');
            $table->string('transaction_ref')->nullable();
            $table->timestamp('purchased_at');
            $table->timestamp('access_expires_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'resource_type', 'resource_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_purchases');
    }
};
