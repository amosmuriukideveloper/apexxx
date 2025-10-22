<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('tutoring_sessions')->cascadeOnDelete();
            $table->string('feedback_by'); // 'student' or 'tutor'
            $table->unsignedBigInteger('feedback_by_id');
            $table->decimal('rating', 3, 2);
            $table->text('feedback');
            $table->json('strengths')->nullable();
            $table->json('improvements')->nullable();
            $table->timestamps();
            
            $table->index(['feedback_by', 'feedback_by_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_feedbacks');
    }
};
