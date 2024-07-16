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
        Schema::create('pivot_notification_types_websites', function (Blueprint $table) {
            $table->id('pivot_notification_types_websites_id');
            $table->foreignId('website_id')->constrained('websites', 'website_id')->onDelete('cascade')->index()->name('pivot_notification_types_websites_website_id');
            $table->foreignId('notification_type_id')->constrained('notification_types', 'notification_type_id')->onDelete('cascade')->index()->name('pivot_notification_types_websites_notification_type_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_notification_types_websites');
    }
};
