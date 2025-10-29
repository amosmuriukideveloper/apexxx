<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('lecture_id')->nullable(); // Changed from lesson_id to lecture_id
            $table->string('activity_type')->default('lesson_completion'); // lesson_completion, quiz_attempt, etc.
            $table->integer('progress_value')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Add foreign key to course_lectures if it exists
            $table->foreign('lecture_id')->references('id')->on('course_lectures')->nullOnDelete();
            
            $table->index(['user_id', 'updated_at']);
            $table->index(['user_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
