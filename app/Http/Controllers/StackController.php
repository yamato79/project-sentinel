<?php

namespace App\Http\Controllers;

use App\Models\Stack;
use App\Models\Website;
use App\Http\Resources\StackResource;
use App\Http\Resources\WebsiteResource;
use App\Http\Requests\Stack\CreateStackRequest;
use App\Http\Requests\Stack\UpdateStackRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class StackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', new Stack());

        $stacks = Stack::query()
            ->withCount([
                'users',
                'websites',
            ])
            ->when($request->filled('searchQuery'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->get('searchQuery') . '%');
            })
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('panel/stacks/browse', [
            'stacks' => StackResource::collection($stacks),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', new Stack);

        return Inertia::render('panel/stacks/create', [
            // ...
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateStackRequest $request)
    {
        Gate::authorize('create', new Stack());

        $payload = $request->safe()->only([
            'name',
            'slug',
            'description',
        ]);

        $stack = Stack::create($payload);

        return redirect()->route('panel.stacks.edit', [
            'stack' => $stack,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editWebsites(Request $request, Stack $stack)
    {
        Gate::authorize('view', $stack);

        $stack->load([
            'websites',
        ]);

        $websites = Website::query()
            ->where('created_by_user_id', auth()->user()->getKey())
            ->get();

        return Inertia::render('panel/stacks/edit/websites', [
            'stack' => new StackResource($stack),
            'websites' => WebsiteResource::collection($websites),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editMembers(Request $request, Stack $stack)
    {
        Gate::authorize('view', $stack);

        $stack->load([
            'users',
        ]);

        return Inertia::render('panel/stacks/edit/members', [
            'stack' => new StackResource($stack),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editSettings(Request $request, Stack $stack)
    {
        Gate::authorize('view', $stack);

        return Inertia::render('panel/stacks/edit/settings', [
            'stack' => new StackResource($stack),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStackRequest $request, Stack $stack)
    {
        Gate::authorize('update', $stack);

        $stack->update($request->safe()->only([
            'name',
            'slug',
            'description',
        ]));

        return redirect()->back()->with([
            'stack' => $stack,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stack $stack)
    {
        Gate::authorize('delete', $stack);

        $stack->delete();

        return redirect()->route('panel.stacks.index');
    }
}
