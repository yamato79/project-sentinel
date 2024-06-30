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
        Schema::create('pivot_stacks_users', function (Blueprint $table) {
            $table->id('pivot_stacks_users_id');
            $table->foreignId('stack_id')->constrained('stacks', 'stack_id')->onDelete('cascade')->index()->name('fk_pivot_stacks_users_stack_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade')->index()->name('fk_pivot_stacks_users_user_id');
            $table->boolean('can_view')->default(true);
            $table->boolean('can_edit')->default(false);
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();
        });

        Schema::table('pivot_stacks_users', function (Blueprint $table) {
            $table->unique(['stack_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stack_users');
    }
};
