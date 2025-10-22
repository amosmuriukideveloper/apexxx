<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_forms', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_type'); // Expert, Tutor, ContentCreator
            $table->unsignedBigInteger('applicant_id');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('education_level')->nullable();
            $table->string('institution')->nullable();
            $table->string('field_of_study')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->json('expertise_areas')->nullable();
            $table->text('why_join')->nullable();
            $table->string('sample_work_url')->nullable();
            $table->string('linkedin_profile')->nullable();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();
            
            $table->index(['applicant_type', 'applicant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_forms');
    }
};
