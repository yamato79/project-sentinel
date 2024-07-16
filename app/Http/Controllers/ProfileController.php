<?php

namespace App\Http\Controllers;

use App\Http\Requests\Me\CreateSubscriptionRequest;
use App\Http\Requests\Me\UpdateSubscriptionRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('panel/me/profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'breadcrumbs' => [
                ['label' => 'Me', 'href' => route('panel.me.profile')],
                ['label' => 'Profile', 'href' => route('panel.me.profile')],
            ],
            'tabs' => $this->getTabs(),
        ]);
    }

    /**
     * Display the user's billing page.
     */
    public function getBillingPage(Request $request): Response
    {
        $user = auth()->user();
        $transactions = $user->transactions;
        $subscription = $user->subscription('default');
        $lastPayment = ($subscription) ? $subscription->lastPayment() : null;
        $nextPayment = ($subscription) ? $subscription->nextPayment() : null;

        $prices = $user->previewPrices([
            'pri_01j26186n36dt6x8tw9g6ph8tj', // FREE
            'pri_01j261672d1xbp34acdver06bs', // STANDARD
            'pri_01j24jkwkw2jgvg8jv03tdyzx3', // PRO
            'pri_01j2rt004a5zmhd9sjtsxr2anb', // ULTRA
        ]);

        return Inertia::render('panel/me/billing', [
            'prices' => $prices,
            'subscription' => $subscription,
            'transactions' => $transactions,
            'paymentMethodUpdateUrl' => ($subscription) ? $subscription->paymentMethodUpdateUrl() : '',
            'cancelUrl' => ($subscription) ? $subscription->cancelUrl() : '',
            'lastPayment' => $lastPayment,
            'nextPayment' => $nextPayment,
            'breadcrumbs' => [
                ['label' => 'Me', 'href' => route('panel.me.billing')],
                ['label' => 'Billing', 'href' => route('panel.me.billing')],
            ],
            'tabs' => $this->getTabs(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('panel.me.profile');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Get the tabs available for the user.
     */
    public function getTabs()
    {
        $tabs = [
            [
                'label' => 'Profile',
                'href' => route('panel.me.profile'),
            ],
            [
                'label' => 'Billing',
                'href' => route('panel.me.billing'),
            ],
        ];

        return $tabs;
    }

    /**
     * Create a subscription for the user.
     */
    public function createSubscription(CreateSubscriptionRequest $request)
    {
        $checkout = $user->subscribe($request->validated()['price_id'], 'default')
            ->returnTo(route('panel.dashboard'));

        $options = $checkout->options();
        $options['settings']['displayMode'] = 'overlay';
        $options['settings']['allowLogout'] = false;

        return redirect()->back()->with('flash', [
            'paddleOptions' => $options,
        ]);
    }

    /**
     * Update a subscription for the user.
     */
    public function updateSubscription(UpdateSubscriptionRequest $request)
    {
        $swap = auth()->user()
            ->subscription('default')
            ->doNotBill()
            ->swap($request->validated()['price_id']);

        return redirect()->back()->with('flash', [
            'swap' => $swap,
        ]);
    }

    /**
     * Cancel the subscription for the user.
     */
    public function cancelSubscription()
    {
        return redirect()->back()->with('flash', [
            'cancel' => auth()->user()->subscription('default')->cancel(),
        ]);
    }
}
