<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only create if the table doesn't exist
        if (!Schema::hasTable('user_progress')) {
            Schema::create('user_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
                $table->unsignedBigInteger('lecture_id')->nullable();
                $table->string('activity_type')->default('lesson_completion');
                $table->integer('progress_value')->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->index(['user_id', 'updated_at']);
                $table->index(['user_id', 'course_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
