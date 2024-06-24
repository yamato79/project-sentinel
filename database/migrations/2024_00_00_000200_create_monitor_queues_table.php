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
        Schema::create('monitor_queue', function (Blueprint $table) {
            $table->id('monitor_queue_id');
            $table->foreignId('monitor_type_id')->references('monitor_type_id')->on('monitor_types')->onDelete('cascade')->index()->name('fk_monitor_queues_monitor_type_id');
            $table->foreignId('website_id')->references('website_id')->on('websites')->onDelete('cascade')->index()->name('fk_monitor_queues_website_id');
            $table->jsonb('raw_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitor_queues');
    }
};
