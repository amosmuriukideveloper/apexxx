<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('expert_id')->constrained();
            $table->enum('submission_type', ['draft', 'final', 'revision']);
            $table->integer('version_number')->default(1);
            $table->text('description')->nullable();
            $table->string('turnitin_report_path')->nullable();
            $table->string('ai_detection_report_path')->nullable();
            $table->decimal('turnitin_score', 5, 2)->nullable();
            $table->decimal('ai_detection_score', 5, 2)->nullable();
            $table->enum('admin_review_status', ['pending', 'approved', 'revision_required', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_submissions');
    }
};
