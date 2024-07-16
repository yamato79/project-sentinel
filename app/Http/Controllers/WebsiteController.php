<?php

namespace App\Http\Controllers;

use App\Http\Requests\Website\CreateWebsiteRequest;
use App\Http\Requests\Website\DeleteWebsiteRequest;
use App\Http\Requests\Website\UpdateWebsiteRequest;
use App\Http\Requests\Website\UpdateWebsiteNotificationChannelsRequest;
use App\Http\Requests\Website\UpdateWebsiteNotificationSettingsRequest;
use App\Http\Resources\MonitorLocationResource;
use App\Http\Resources\NotificationChannelDriverResource;
use App\Http\Resources\NotificationTypeResource;
use App\Http\Resources\WebsiteResource;
use App\Models\MonitorLocation;
use App\Models\NotificationChannelDriver;
use App\Models\NotificationType;
use App\Models\Website;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $websites = Website::query()
            ->with(['websiteStatus', 'stacks'])
            ->when($request->filled('searchQuery'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->get('searchQuery').'%');
            })
            ->availableToUser(auth()->user())
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('panel/websites/browse', [
            'websites' => WebsiteResource::collection($websites),
            'breadcrumbs' => [
                ['label' => 'Websites', 'href' => route('panel.websites.index')],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', new Website());

        $monitorLocations = MonitorLocation::query()
            ->where('is_active', true)
            ->get();

        return Inertia::render('panel/websites/create', [
            'monitorLocations' => MonitorLocationResource::collection($monitorLocations),
            'breadcrumbs' => [
                ['label' => 'Websites', 'href' => route('panel.websites.index')],
                ['label' => 'Create', 'href' => route('panel.websites.create')],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWebsiteRequest $request)
    {
        DB::beginTransaction();

        try {
            $website = Website::create($request->safe()->only([
                'name',
                'address',
                'website_status_id',
            ]));

            $website->monitorLocations()
                ->sync($request->validated()['monitor_location_ids']);

            $website->monitorLocations->each(function ($monitorLocation) use ($website) {
                \App\Jobs\Monitors\CheckDomainExpiration::dispatch($website, $monitorLocation);
                \App\Jobs\Monitors\CheckDomainNameservers::dispatch($website, $monitorLocation);
                // \App\Jobs\Monitors\CheckLighthouse::dispatch($website, $monitorLocation);
                \App\Jobs\Monitors\CheckResponseCode::dispatch($website, $monitorLocation);
                \App\Jobs\Monitors\CheckResponseTime::dispatch($website, $monitorLocation);
                \App\Jobs\Monitors\CheckSSLExpiration::dispatch($website, $monitorLocation);
                \App\Jobs\Monitors\CheckSSLValidity::dispatch($website, $monitorLocation);
            });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            logger()->error('Error creating new website.', [
                'raw' => $e->getMessage(),
            ]);

            return redirect()->back();
        }

        return redirect()->route('panel.websites.edit.summary', [
            'website' => $website,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteRequest $request, Website $website)
    {
        $website->update($request->safe()->only([
            'name',
            'address',
            'website_status_id',
        ]));

        $website->monitorLocations()
            ->sync($request->validated()['monitor_location_ids']);

        return redirect()->back()->with([
            'website' => $website,
        ]);
    }

    /**
     * Update the specified resource's notification settings in storage.
     */
    public function updateNotificationSettings(UpdateWebsiteNotificationSettingsRequest $request, Website $website)
    {
        $website->notificationTypes()->sync($request->validated()['notification_type_ids']);
        return redirect()->back();
    }

    /**
     * Update the specified resource's notification channels in storage.
     */
    public function updateNotificationChannels(UpdateWebsiteNotificationChannelsRequest $request, Website $website)
    {
        foreach($request->validated()['notification_channels'] as $notificationChannelDriverId => $data) {
            logger()->info($notificationChannelDriverId, [
                'data' => $data,
                'raw' => $request->validated(),
            ]);

            $notificationChannel = $website->notificationChannels()
                ->updateOrCreate([
                    'notification_channel_driver_id' => $notificationChannelDriverId,
                ], [
                    'name' => $notificationChannelDriverId,
                    'field_values' => $data['field_values'],
                    'is_active' => $data['is_active'],
                ]);
        }
        
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteWebsiteRequest $request, Website $website)
    {
        $website->delete();

        return redirect()->route('panel.websites.index');
    }

    /**
     * Get the tabs available for the user.
     */
    public function getEditTabs(Website $website)
    {
        $tabs = [
            [
                'label' => 'Summary',
                'href' => route('panel.websites.edit.summary', [
                    'website' => $website,
                ]),
            ],
        ];

        if (Gate::check('update', $website)) {
            $tabs[] = [
                'label' => 'Notifications',
                'href' => route('panel.websites.edit.notifications', [
                    'website' => $website,
                ]),
            ];

            $tabs[] = [
                'label' => 'Settings',
                'href' => route('panel.websites.edit.settings', [
                    'website' => $website,
                ]),
            ];
        }

        return $tabs;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function getSummaryPage(Request $request, Website $website)
    {
        Gate::authorize('view', $website);

        $website->load([
            'websiteStatus',
        ]);

        return Inertia::render('panel/websites/edit/summary', [
            'website' => new WebsiteResource($website),
            'tabs' => $this->getEditTabs($website),
            'breadcrumbs' => [
                ['label' => 'Websites', 'href' => route('panel.websites.index')],
                ['label' => $website->name, 'href' => route('panel.websites.edit', ['website' => $website])],
                ['label' => 'Summary', 'href' => route('panel.websites.edit.summary', ['website' => $website])],
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function getNotificationsPage(Request $request, Website $website)
    {
        Gate::authorize('view', $website);
        Gate::authorize('update', $website);

        $website->load([
            'monitorLocations' => function ($query) {
                $query->where('monitor_locations.is_active', true);
            },
            'notificationChannels',
            'notificationTypes' => function ($query) {
                $query->where('notification_types.is_active', true);
            },
            'websiteStatus',
        ]);

        $monitorLocations = MonitorLocation::query()
            ->where('is_active', true)
            ->get();

        return Inertia::render('panel/websites/edit/notifications', [
            'website' => new WebsiteResource($website),
            'tabs' => $this->getEditTabs($website),
            'monitorLocations' => MonitorLocationResource::collection($monitorLocations),
            'notificationChannelDrivers' => NotificationChannelDriverResource::collection(NotificationChannelDriver::where('is_active', true)->get()),
            'notificationTypes' => NotificationTypeResource::collection(NotificationType::where('is_active', true)->get()),
            'breadcrumbs' => [
                ['label' => 'Websites', 'href' => route('panel.websites.index')],
                ['label' => $website->name, 'href' => route('panel.websites.edit', ['website' => $website])],
                ['label' => 'Notifications', 'href' => route('panel.websites.edit.notifications', ['website' => $website])],
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function getSettingsPage(Request $request, Website $website)
    {
        Gate::authorize('view', $website);
        Gate::authorize('update', $website);

        $website->load([
            'monitorLocations' => function ($query) {
                $query->where('is_active', true);
            },
            'websiteStatus',
        ]);

        $monitorLocations = MonitorLocation::query()
            ->where('is_active', true)
            ->get();

        return Inertia::render('panel/websites/edit/settings', [
            'website' => new WebsiteResource($website),
            'tabs' => $this->getEditTabs($website),
            'monitorLocations' => MonitorLocationResource::collection($monitorLocations),
            'breadcrumbs' => [
                ['label' => 'Websites', 'href' => route('panel.websites.index')],
                ['label' => $website->name, 'href' => route('panel.websites.edit', ['website' => $website])],
                ['label' => 'Settings', 'href' => route('panel.websites.edit.settings', ['website' => $website])],
            ],
        ]);
    }
}
