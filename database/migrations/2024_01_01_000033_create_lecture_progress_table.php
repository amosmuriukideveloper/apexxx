<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecture_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('course_enrollments')->cascadeOnDelete();
            $table->foreignId('lecture_id')->constrained('course_lectures')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users');
            $table->boolean('is_completed')->default(false);
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->integer('time_spent_seconds')->default(0);
            $table->integer('last_position_seconds')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['enrollment_id', 'lecture_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecture_progress');
    }
};
