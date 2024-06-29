<?php

namespace App\Http\Controllers;

use App\Http\Requests\Website\CreateWebsiteRequest;
use App\Http\Requests\Website\DeleteWebsiteRequest;
use App\Http\Requests\Website\UpdateWebsiteRequest;
use App\Http\Resources\WebsiteResource;
use App\Models\Website;
use App\Services\UptimeService\UptimeService;
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

        return Inertia::render('panel/websites/create', [
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
        $website = Website::create($request->safe()->only([
            'name',
            'address',
            'website_status_id',
        ]));

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

        return redirect()->back()->with([
            'website' => $website,
        ]);
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
            'uptimeCards' => UptimeService::getUptimeCards([
                24 => 'Uptime (24H)',
                168 => 'Uptime (7D)',
                720 => 'Uptime (30D)',
            ], $website->getKey()),
            'uptimeTrend' => UptimeService::getUptimeTrendChartData(24, $website->getKey()),
            'uptimeFeed' => UptimeService::getUptimeFeed(1, $website->getKey()),
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
    public function getSettingsPage(Request $request, Website $website)
    {
        Gate::authorize('view', $website);
        Gate::authorize('update', $website);

        $website->load([
            'websiteStatus',
        ]);

        return Inertia::render('panel/websites/edit/settings', [
            'website' => new WebsiteResource($website),
            'tabs' => $this->getEditTabs($website),
            'breadcrumbs' => [
                ['label' => 'Websites', 'href' => route('panel.websites.index')],
                ['label' => $website->name, 'href' => route('panel.websites.edit', ['website' => $website])],
                ['label' => 'Settings', 'href' => route('panel.websites.edit.settings', ['website' => $website])],
            ],
        ]);
    }
}
