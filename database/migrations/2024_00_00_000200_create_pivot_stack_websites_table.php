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
        Schema::create('pivot_stacks_websites', function (Blueprint $table) {
            $table->id('pivot_stacks_websites_id');
            $table->foreignId('stack_id')->constrained('stacks', 'stack_id')->onDelete('cascade')->index()->name('fk_pivot_stacks_websites_stack_id');
            $table->foreignId('website_id')->constrained('websites', 'website_id')->onDelete('cascade')->index()->name('fk_pivot_stacks_websites_website_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stack_websites');
    }
};
