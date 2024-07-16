<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::group(['as' => 'panel.', 'middleware' => ['auth', 'verified']], function () {

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/me/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('me.profile');

    Route::patch('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/me/billing', [\App\Http\Controllers\ProfileController::class, 'getBillingPage'])
        ->name('me.billing');

    Route::patch('/me/billing/update', [\App\Http\Controllers\ProfileController::class, 'updateBilling'])
        ->name('me.billing.update');

    Route::post('/me/billing/subscriptions', [\App\Http\Controllers\ProfileController::class, 'createSubscription'])
        ->name('me.billing.subscriptions.store');

    Route::patch('/me/billing/subscriptions', [\App\Http\Controllers\ProfileController::class, 'updateSubscription'])
        ->name('me.billing.subscriptions.update');

    Route::delete('/me/billing/subscriptions', [\App\Http\Controllers\ProfileController::class, 'cancelSubscription'])
        ->name('me.billing.subscriptions.destroy');

    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::view('/checkout', 'checkout');

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

    Route::get('/websites/{website}/edit/notifications', [\App\Http\Controllers\WebsiteController::class, 'getNotificationsPage'])
        ->name('websites.edit.notifications');

    Route::get('/websites/{website}/edit/settings', [\App\Http\Controllers\WebsiteController::class, 'getSettingsPage'])
        ->name('websites.edit.settings');

    Route::patch('/websites/{website}/edit/notifications/settings', [\App\Http\Controllers\WebsiteController::class, 'updateNotificationSettings'])
        ->name('websites.edit.notifications.settings');

    Route::patch('/websites/{website}/edit/notifications/channels', [\App\Http\Controllers\WebsiteController::class, 'updateNotificationChannels'])
        ->name('websites.edit.notifications.channels');

    Route::resource('websites', \App\Http\Controllers\WebsiteController::class)
        ->except(['edit', 'show']);

});

Route::group(['prefix' => '/api', 'as' => 'api.', 'middleware' => ['auth', 'verified']], function () {

    Route::get('widgets/uptime-card', fn (Request $request) => response()->json((new \App\Widgets\UptimeCardWidget($request))->getData()))
        ->name('widgets.uptime-card');

    Route::get('/widgets/uptime-feed', fn (Request $request) => (new \App\Widgets\UptimeFeedWidget($request))->getData())
        ->name('widgets.uptime-feed');

    Route::get('/widgets/uptime-trend', fn (Request $request) => (new \App\Widgets\UptimeTrendWidget($request))->getData())
        ->name('widgets.uptime-trend');

    Route::get('/widgets/response-time-trend', fn (Request $request) => (new \App\Widgets\ResponseTimeTrendWidget($request))->getData())
        ->name('widgets.response-time-trend');

    Route::get('/widgets/ssl-status', fn (Request $request) => (new \App\Widgets\SSLStatusWidget($request))->getData())
        ->name('widgets.ssl-status');

    Route::get('/widgets/ssl-status/execute', fn (Request $request) => (new \App\Widgets\SSLStatusWidget($request))->execute())
        ->name('widgets.ssl-status.execute');

    Route::get('/widgets/domain-status', fn (Request $request) => (new \App\Widgets\DomainStatusWidget($request))->getData())
        ->name('widgets.domain-status');

    Route::get('/widgets/domain-status/execute', fn (Request $request) => (new \App\Widgets\DomainStatusWidget($request))->execute())
        ->name('widgets.domain-status.execute');

    Route::get('/widgets/domain-nameservers-table', fn (Request $request) => (new \App\Widgets\DomainNameserversTableWidget($request))->getData())
        ->name('widgets.domain-nameservers-table');

    Route::get('/widgets/domain-nameservers-table/execute', fn (Request $request) => (new \App\Widgets\DomainNameserversTableWidget($request))->execute())
        ->name('widgets.domain-nameservers-table.execute');

});

require __DIR__.'/auth.php';
