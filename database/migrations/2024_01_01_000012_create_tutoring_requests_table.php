<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutoring_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('tutor_id')->nullable()->constrained('tutors');
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->string('subject');
            $table->string('topic');
            $table->text('description');
            $table->date('preferred_date');
            $table->time('preferred_time');
            $table->integer('session_duration')->default(60); // minutes
            $table->text('learning_goals')->nullable();
            $table->enum('status', ['pending', 'assigned', 'scheduled', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutoring_requests');
    }
};
