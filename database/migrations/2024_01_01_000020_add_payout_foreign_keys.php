<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add payout foreign keys
        Schema::table('payout_requests', function (Blueprint $table) {
            $table->foreign('batch_id')->references('id')->on('payout_batches')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('payout_requests', function (Blueprint $table) {
            $table->dropForeign(['batch_id']);
        });
    }
};
