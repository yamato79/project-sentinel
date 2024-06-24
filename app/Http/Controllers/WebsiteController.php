<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Http\Resources\WebsiteResource;
use App\Http\Requests\Website\CreateWebsiteRequest;
use App\Http\Requests\Website\UpdateWebsiteRequest;
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
        Gate::authorize('viewAny', new Website());

        $websites = Website::query()
            ->with(['websiteStatus'])
            ->when($request->filled('searchQuery'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->get('searchQuery') . '%');
            })
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('panel/websites/browse', [
            'websites' => WebsiteResource::collection($websites),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', new Website);

        return Inertia::render('panel/websites/create', [
            // ...
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWebsiteRequest $request)
    {
        Gate::authorize('create', $website);

        $payload = Website::create($request->safe()->only([
            'name',
            'slug',
            'address',
            'website_status_id',
        ]));

        $website = Website::create($payload);

        return redirect()->route('panel.websites.edit.summary', [
            'website' => $website,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editSummary(Request $request, Website $website)
    {
        Gate::authorize('view', $website);

        $website->load([
            'websiteStatus',
        ]);

        return Inertia::render('panel/websites/edit/summary', [
            'website' => new WebsiteResource($website),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editSettings(Request $request, Website $website)
    {
        Gate::authorize('view', $website);

        $website->load([
            'websiteStatus',
        ]);

        return Inertia::render('panel/websites/edit/settings', [
            'website' => new WebsiteResource($website),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteRequest $request, Website $website)
    {
        Gate::authorize('update', $website);

        $website->update($request->safe()->only([
            'name',
            'slug',
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
    public function destroy(Website $website)
    {
        Gate::authorize('delete', $website);

        $website->delete();

        return redirect()->route('panel.websites.index');
    }
}
