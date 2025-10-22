<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->json('subjects')->nullable();
            $table->integer('teaching_experience_years')->default(0);
            $table->text('bio')->nullable();
            $table->enum('application_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->boolean('documents_verified')->default(false);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_sessions_completed')->default(0);
            $table->decimal('total_earnings', 10, 2)->default(0);
            $table->boolean('available')->default(true);
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->string('profile_photo')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
