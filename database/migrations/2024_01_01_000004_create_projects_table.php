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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('subject');
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced', 'expert']);
            $table->datetime('deadline');
            $table->decimal('budget', 10, 2)->nullable();
            $table->enum('status', [
                'pending', 
                'assigned', 
                'in_progress', 
                'review', 
                'revision_requested', 
                'completed', 
                'cancelled'
            ])->default('pending');
            
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_expert_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->text('admin_notes')->nullable();
            $table->text('revision_notes')->nullable();
            $table->json('attachments')->nullable();
            $table->json('deliverables')->nullable();
            
            $table->timestamps();
            
            $table->index(['status', 'deadline']);
            $table->index(['student_id', 'status']);
            $table->index(['assigned_expert_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
