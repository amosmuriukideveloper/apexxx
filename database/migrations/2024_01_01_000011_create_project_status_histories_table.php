<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('old_status');
            $table->string('new_status');
            $table->string('changed_by_type');
            $table->unsignedBigInteger('changed_by_id');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['changed_by_type', 'changed_by_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_status_histories');
    }
};
