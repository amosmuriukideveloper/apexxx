<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('content_creators');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_description', 500);
            $table->text('description');
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->json('tags')->nullable();
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            $table->string('language')->default('en');
            $table->string('thumbnail_path')->nullable();
            $table->string('promo_video_path')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->boolean('is_free')->default(false);
            $table->enum('status', ['draft', 'under_review', 'published', 'unpublished'])->default('draft');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->integer('total_duration_minutes')->default(0);
            $table->integer('total_lectures')->default(0);
            $table->integer('total_enrollments')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'approval_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
