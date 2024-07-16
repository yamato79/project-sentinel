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
        Schema::create('notification_channel_drivers', function (Blueprint $table) {
            $table->id('notification_channel_driver_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('class')->unique();
            $table->string('description')->nullable();
            $table->jsonb('fields');
            $table->jsonb('validator_rules');
            $table->jsonb('validator_messages');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_channel_drivers');
    }
};
