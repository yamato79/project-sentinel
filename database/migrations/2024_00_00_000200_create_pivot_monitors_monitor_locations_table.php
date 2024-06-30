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
        Schema::create('pivot_monitor_locations_websites', function (Blueprint $table) {
            $table->id('pivot_monitor_locations_websites_id');
            $table->foreignId('website_id')->constrained('websites', 'website_id')->onDelete('cascade')->index()->name('pivot_monitor_locations_websites_website_id');
            $table->foreignId('monitor_location_id')->constrained('monitor_locations', 'monitor_location_id')->onDelete('cascade')->index()->name('pivot_monitor_locations_websites_monitor_location_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_monitor_locations_websites');
    }
};
