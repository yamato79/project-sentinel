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
        Schema::create('notification_channels', function (Blueprint $table) {
            $table->id('notification_channel_id');
            $table->string('name');
            $table->jsonb('field_values')->nullable();
            $table->boolean('is_active')->default(false);
            $table->foreignId('notification_channel_driver_id')->references('notification_channel_driver_id')->on('notification_channel_drivers')->onDelete('cascade')->index()->name('fk_notification_channels_notification_channel_driver_id');
            $table->foreignId('website_id')->references('website_id')->on('websites')->onDelete('cascade')->index()->name('fk_notification_channels_website_id');
            $table->timestamps();
        });

        Schema::table('notification_channels', function ($table) {
            $table->unique(['notification_channel_driver_id', 'website_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_channels');
    }
};
