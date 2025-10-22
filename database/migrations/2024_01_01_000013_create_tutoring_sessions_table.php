<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutoring_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('tutoring_requests');
            $table->foreignId('tutor_id')->constrained('tutors');
            $table->foreignId('student_id')->constrained('users');
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            $table->integer('duration_minutes')->default(60);
            $table->string('google_meet_link')->nullable();
            $table->string('calendar_event_id')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->text('session_notes')->nullable();
            $table->string('recording_url')->nullable();
            $table->enum('attendance_status', ['both_present', 'student_absent', 'tutor_absent', 'both_absent'])->nullable();
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->decimal('session_fee', 10, 2);
            $table->decimal('platform_commission', 10, 2);
            $table->decimal('tutor_earnings', 10, 2);
            $table->decimal('student_rating', 3, 2)->nullable();
            $table->text('student_feedback')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutoring_sessions');
    }
};
