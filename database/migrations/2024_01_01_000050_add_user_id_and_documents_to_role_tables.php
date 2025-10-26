<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add user_id and document fields to experts table
        Schema::table('experts', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained()->onDelete('cascade');
            $table->string('cv_document')->nullable()->after('profile_photo');
            $table->json('certificates')->nullable()->after('cv_document');
            $table->string('id_document')->nullable()->after('certificates');
            $table->text('admin_notes')->nullable()->after('rejection_reason');
        });

        // Add user_id and document fields to tutors table
        Schema::table('tutors', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained()->onDelete('cascade');
            $table->string('cv_document')->nullable()->after('profile_photo');
            $table->json('certificates')->nullable()->after('cv_document');
            $table->string('id_document')->nullable()->after('certificates');
            $table->text('admin_notes')->nullable()->after('rejection_reason');
        });

        // Add user_id and document fields to content_creators table
        Schema::table('content_creators', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained()->onDelete('cascade');
            $table->string('cv_document')->nullable()->after('profile_photo');
            $table->json('certificates')->nullable()->after('cv_document');
            $table->string('id_document')->nullable()->after('certificates');
            $table->text('admin_notes')->nullable()->after('rejection_reason');
        });
    }

    public function down(): void
    {
        Schema::table('experts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'cv_document', 'certificates', 'id_document', 'admin_notes']);
        });

        Schema::table('tutors', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'cv_document', 'certificates', 'id_document', 'admin_notes']);
        });

        Schema::table('content_creators', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'cv_document', 'certificates', 'id_document', 'admin_notes']);
        });
    }
};
