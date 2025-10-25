<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add course foreign keys (resolves circular dependency between enrollments and certificates)
        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->foreign('certificate_id')->references('id')->on('course_certificates')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->dropForeign(['certificate_id']);
        });
    }
};
