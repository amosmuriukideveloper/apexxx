<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_id')->constrained('course_lectures')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('passing_score')->default(70); // percentage
            $table->integer('time_limit_minutes')->nullable();
            $table->integer('max_attempts')->default(3);
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('show_correct_answers')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_quizzes');
    }
};
