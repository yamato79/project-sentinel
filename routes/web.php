<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/', '/login');

Route::group(['as' => 'panel.', 'middleware' => ['auth', 'verified']], function () {

    Route::get('/dashboard', fn () => Inertia::render('panel/dashboard/index', [
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'href' => route('panel.dashboard')],
        ],
    ]))->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /**
     * Stacks
     */
    Route::get('/stacks/{stack}/invitation', [\App\Http\Controllers\StackController::class, 'getInvitationPage'])
        ->name('stacks.invitation')
        ->middleware(\App\Http\Middleware\StackInviteAccepted::class);

    Route::get('/stacks/{stack}/edit', fn ($stack) => redirect()->route('panel.stacks.edit.websites', ['stack' => $stack]))
        ->name('stacks.edit');

    Route::get('/stacks/{stack}/edit/websites', [\App\Http\Controllers\StackController::class, 'getWebsitesPage'])
        ->name('stacks.edit.websites')
        ->middleware(\App\Http\Middleware\StackInvitePending::class);

    Route::get('/stacks/{stack}/edit/members', [\App\Http\Controllers\StackController::class, 'getUsersPage'])
        ->name('stacks.edit.users')
        ->middleware(\App\Http\Middleware\StackInvitePending::class);

    Route::get('/stacks/{stack}/edit/settings', [\App\Http\Controllers\StackController::class, 'getSettingsPage'])
        ->name('stacks.edit.settings')
        ->middleware(\App\Http\Middleware\StackInvitePending::class);

    // Websites
    Route::post('/stacks/{stack}/websites/attach', [\App\Http\Controllers\StackController::class, 'attachWebsite'])
        ->name('stacks.websites.attach');

    Route::post('/stacks/{stack}/websites/detach', [\App\Http\Controllers\StackController::class, 'detachWebsite'])
        ->name('stacks.websites.detach');

    // Members
    Route::post('/stacks/{stack}/members/invite', [\App\Http\Controllers\StackController::class, 'attachUser'])
        ->name('stacks.users.attach');

    Route::post('/stacks/{stack}/members/update', [\App\Http\Controllers\StackController::class, 'updateUser'])
        ->name('stacks.users.update');

    Route::post('/stacks/{stack}/members/remove', [\App\Http\Controllers\StackController::class, 'detachUser'])
        ->name('stacks.users.detach');

    Route::delete('/stacks/{stack}/members/leave', [\App\Http\Controllers\StackController::class, 'leaveStack'])
        ->name('stacks.users.leave');

    Route::post('/stacks/{stack}/members/accept-invite', [\App\Http\Controllers\StackController::class, 'acceptStackInvite'])
        ->name('stacks.users.accept-invite');

    Route::post('/stacks/{stack}/members/reject-invite', [\App\Http\Controllers\StackController::class, 'rejectStackInvite'])
        ->name('stacks.users.reject-invite');

    Route::resource('stacks', \App\Http\Controllers\StackController::class)
        ->except(['edit', 'show']);

    /**
     * Websites
     */
    Route::get('/websites', fn () => redirect()->route('panel.websites.index'))
        ->name('websites');

    Route::get('/websites/{website}/edit', fn ($website) => redirect()->route('panel.websites.edit.summary', ['website' => $website]))
        ->name('websites.edit');

    Route::get('/websites/{website}/edit/summary', [\App\Http\Controllers\WebsiteController::class, 'getSummaryPage'])
        ->name('websites.edit.summary');

    Route::get('/websites/{website}/edit/settings', [\App\Http\Controllers\WebsiteController::class, 'getSettingsPage'])
        ->name('websites.edit.settings');

    Route::resource('websites', \App\Http\Controllers\WebsiteController::class)
        ->except(['edit', 'show']);

});

require __DIR__.'/auth.php';
