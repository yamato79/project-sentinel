<?php

use App\Models\WebsiteStatus;
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
        Schema::create('websites', function (Blueprint $table) {
            $table->id('website_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('address');
            $table->foreignId('website_status_id')->default(WebsiteStatus::DEFAULT)->references('website_status_id')->on('website_statuses')->onDelete('cascade')->index()->name('fk_websites_website_id');
            $table->foreignId('created_by_user_id')->references('user_id')->on('users')->onDelete('cascade')->index()->name('fk_websites_created_by_user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
