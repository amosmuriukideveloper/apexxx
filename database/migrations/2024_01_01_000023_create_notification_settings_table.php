<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('channel', ['email', 'sms', 'push', 'database']);
            $table->string('event_type'); // project_assigned, payment_received, etc.
            $table->boolean('is_enabled')->default(true);
            $table->text('template')->nullable();
            $table->json('recipients')->nullable(); // Roles/users to notify
            $table->timestamps();
            
            $table->unique(['channel', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
