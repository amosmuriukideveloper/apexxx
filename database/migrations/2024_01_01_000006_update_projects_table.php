<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('projects', 'project_number')) {
                $table->string('project_number')->unique()->after('id');
            }
            if (!Schema::hasColumn('projects', 'expert_id')) {
                $table->foreignId('expert_id')->nullable()->constrained('experts')->after('student_id');
            }
            if (!Schema::hasColumn('projects', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->constrained('users')->after('expert_id');
            }
            if (!Schema::hasColumn('projects', 'project_type')) {
                $table->string('project_type')->after('description');
            }
            if (!Schema::hasColumn('projects', 'complexity_level')) {
                $table->enum('complexity_level', ['basic', 'intermediate', 'advanced'])->after('project_type');
            }
            if (!Schema::hasColumn('projects', 'subject_area')) {
                $table->string('subject_area')->after('complexity_level');
            }
            if (!Schema::hasColumn('projects', 'requirements')) {
                $table->json('requirements')->nullable()->after('subject_area');
            }
            if (!Schema::hasColumn('projects', 'word_count')) {
                $table->integer('word_count')->nullable()->after('requirements');
            }
            if (!Schema::hasColumn('projects', 'page_count')) {
                $table->integer('page_count')->nullable()->after('word_count');
            }
            if (!Schema::hasColumn('projects', 'platform_commission')) {
                $table->decimal('platform_commission', 10, 2)->default(0)->after('budget');
            }
            if (!Schema::hasColumn('projects', 'expert_earnings')) {
                $table->decimal('expert_earnings', 10, 2)->default(0)->after('platform_commission');
            }
            if (!Schema::hasColumn('projects', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending')->after('status');
            }
            if (!Schema::hasColumn('projects', 'quality_score')) {
                $table->decimal('quality_score', 3, 2)->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('projects', 'turnitin_score')) {
                $table->decimal('turnitin_score', 5, 2)->nullable()->after('quality_score');
            }
            if (!Schema::hasColumn('projects', 'ai_detection_score')) {
                $table->decimal('ai_detection_score', 5, 2)->nullable()->after('turnitin_score');
            }
            if (!Schema::hasColumn('projects', 'student_rating')) {
                $table->decimal('student_rating', 3, 2)->nullable()->after('ai_detection_score');
            }
            if (!Schema::hasColumn('projects', 'student_review')) {
                $table->text('student_review')->nullable()->after('student_rating');
            }
            if (!Schema::hasColumn('projects', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('student_review');
            }
            if (!Schema::hasColumn('projects', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('assigned_at');
            }
            if (!Schema::hasColumn('projects', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('started_at');
            }
            if (!Schema::hasColumn('projects', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('submitted_at');
            }
            
            // Add soft deletes if not exists
            if (!Schema::hasColumn('projects', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $columns = [
                'project_number', 'expert_id', 'admin_id', 'project_type', 'complexity_level',
                'subject_area', 'requirements', 'word_count', 'page_count', 'platform_commission',
                'expert_earnings', 'payment_status', 'quality_score', 'turnitin_score',
                'ai_detection_score', 'student_rating', 'student_review', 'assigned_at',
                'started_at', 'submitted_at', 'completed_at', 'deleted_at'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('projects', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
