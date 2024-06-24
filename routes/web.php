<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/', '/login');

Route::group(['as' => 'panel.', 'middleware' => ['auth', 'verified']], function () {

    Route::get('/dashboard', fn () => Inertia::render('panel/dashboard/index'))
        ->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /**
     * Stacks
     */
    Route::get('/stacks', fn () => redirect()->route('panel.stacks.index'))
        ->name('stacks');

    Route::get('/stacks/{stack}/edit', fn ($stack) => redirect()->route('panel.stacks.edit.websites', ['stack' => $stack]))
        ->name('stacks.edit');

    Route::get('/stacks/{stack}/edit/websites', [\App\Http\Controllers\StackController::class, 'editWebsites'])
        ->name('stacks.edit.websites');
        
    Route::get('/stacks/{stack}/edit/members', [\App\Http\Controllers\StackController::class, 'editMembers'])
        ->name('stacks.edit.members');

    Route::get('/stacks/{stack}/edit/settings', [\App\Http\Controllers\StackController::class, 'editSettings'])
        ->name('stacks.edit.settings');

    Route::resource('stacks', \App\Http\Controllers\StackController::class)
        ->except(['edit', 'show']);

    /**
     * Websites
     */
    Route::get('/websites', fn () => redirect()->route('panel.websites.index'))
        ->name('websites');

    Route::get('/websites/{website}/edit', fn ($website) => redirect()->route('panel.websites.edit.summary', ['website' => $website]))
        ->name('websites.edit');

    Route::get('/websites/{website}/edit/summary', [\App\Http\Controllers\WebsiteController::class, 'editSummary'])
        ->name('websites.edit.summary');

    Route::get('/websites/{website}/edit/settings', [\App\Http\Controllers\WebsiteController::class, 'editSettings'])
        ->name('websites.edit.settings');

    Route::resource('websites', \App\Http\Controllers\WebsiteController::class)
        ->except(['edit', 'show']);

});

require __DIR__.'/auth.php';
