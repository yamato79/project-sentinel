<?php

namespace App\Http\Controllers;

use App\Http\Requests\Stack\AcceptStackInviteRequest;
use App\Http\Requests\Stack\AttachUserRequest;
use App\Http\Requests\Stack\AttachWebsiteRequest;
use App\Http\Requests\Stack\CreateStackRequest;
use App\Http\Requests\Stack\DeleteStackRequest;
use App\Http\Requests\Stack\DetachUserRequest;
use App\Http\Requests\Stack\DetachWebsiteRequest;
use App\Http\Requests\Stack\RejectStackInviteRequest;
use App\Http\Requests\Stack\UpdateStackRequest;
use App\Http\Requests\Stack\UpdateUserRequest;
use App\Http\Resources\StackResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WebsiteResource;
use App\Models\Stack;
use App\Models\User;
use App\Models\Website;
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
        $currentUser = auth()->user();

        $stacks = Stack::query()
            ->with([
                'users',
                'createdByUser',
            ])
            ->withCount([
                'users',
                'websites',
            ])
            ->when($request->filled('searchQuery'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->get('searchQuery').'%');
            })
            ->orWhere('created_by_user_id', $currentUser->getKey())
            ->orWhereHas('users', function ($query) use ($currentUser) {
                $query->where('pivot_stacks_users.user_id', $currentUser->getKey());
            })
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('panel/stacks/browse', [
            'stacks' => StackResource::collection($stacks),
            'breadcrumbs' => [
                ['label' => 'Stacks', 'href' => route('panel.stacks.index')],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('panel/stacks/create', [
            'breadcrumbs' => [
                ['label' => 'Stacks', 'href' => route('panel.stacks.index')],
                ['label' => 'Create', 'href' => route('panel.stacks.create')],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateStackRequest $request)
    {
        $stack = Stack::create($request->safe()->only([
            'name',
            'description',
        ]));

        return redirect()->route('panel.stacks.edit', [
            'stack' => $stack,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStackRequest $request, Stack $stack)
    {
        $stack->update($request->safe()->only([
            'name',
            'description',
        ]));

        return redirect()->back()->with([
            'stack' => $stack,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteStackRequest $request, Stack $stack)
    {
        $stack->delete();

        return redirect()->route('panel.stacks.index');
    }

    /**
     * ------------------------------------------------------------
     * Stack Pages
     * ------------------------------------------------------------
     */

    /**
     * Get the tabs available for the user.
     */
    public function getEditTabs(Stack $stack)
    {
        $tabs = [
            [
                'label' => 'Websites',
                'href' => route('panel.stacks.edit.websites', [
                    'stack' => $stack,
                ]),
            ],
            [
                'label' => 'Members',
                'href' => route('panel.stacks.edit.users', [
                    'stack' => $stack,
                ]),
            ],
        ];

        if (Gate::check('update', $stack)) {
            $tabs[] = [
                'label' => 'Settings',
                'href' => route('panel.stacks.edit.settings', [
                    'stack' => $stack,
                ]),
            ];
        }

        return $tabs;
    }

    /**
     * Get the stack's invitation page.
     */
    public function getInvitationPage(Stack $stack)
    {
        Gate::authorize('view', $stack);

        $stack->load([
            'createdByUser',
        ]);

        return Inertia::render('panel/stacks/invitation', [
            'stack' => new StackResource($stack),
            'breadcrumbs' => [
                ['label' => 'Stacks', 'href' => route('panel.stacks.index')],
                ['label' => $stack->name, 'href' => route('panel.stacks.edit', ['stack' => $stack])],
                ['label' => 'Invitation', 'href' => route('panel.stacks.invitation', ['stack' => $stack])],
            ],
        ]);
    }

    /**
     * Get the stack's edit users page.
     */
    public function getUsersPage(Stack $stack)
    {
        Gate::authorize('view', $stack);

        $stack->load([
            'users',
            'createdByUser',
        ]);

        $users = $stack
            ->users
            ->prepend($stack->createdByUser);

        return Inertia::render('panel/stacks/edit/users', [
            'stack' => new StackResource($stack),
            'users' => UserResource::collection($stack->users),
            'breadcrumbs' => [
                ['label' => 'Stacks', 'href' => route('panel.stacks.index')],
                ['label' => $stack->name, 'href' => route('panel.stacks.edit', ['stack' => $stack])],
                ['label' => 'Users', 'href' => route('panel.stacks.edit.users', ['stack' => $stack])],
            ],
            'tabs' => $this->getEditTabs($stack),
            'canEdit' => Gate::allows('update', $stack),
        ]);
    }

    /**
     * Get the stack's edit settings page.
     */
    public function getSettingsPage(Stack $stack)
    {
        Gate::authorize('update', $stack);

        return Inertia::render('panel/stacks/edit/settings', [
            'stack' => new StackResource($stack),
            'breadcrumbs' => [
                ['label' => 'Stacks', 'href' => route('panel.stacks.index')],
                ['label' => $stack->name, 'href' => route('panel.stacks.edit', ['stack' => $stack])],
                ['label' => 'Settings', 'href' => route('panel.stacks.edit.settings', ['stack' => $stack])],
            ],
            'tabs' => $this->getEditTabs($stack),
        ]);
    }

    /**
     * Get the stack's edit websites page.
     */
    public function getWebsitesPage(Stack $stack)
    {
        Gate::authorize('view', $stack);

        $stack->load([
            'websites',
        ]);

        $websites = $stack->websites;
        $authWebsites = auth()->user()->websites;
        $stackWebsites = $stack->websites;
        $availableWebsites = $authWebsites->merge($stackWebsites);

        return Inertia::render('panel/stacks/edit/websites', [
            'stack' => new StackResource($stack),
            'websites' => WebsiteResource::collection($websites),
            'availableWebsites' => WebsiteResource::collection($availableWebsites),
            'breadcrumbs' => [
                ['label' => 'Stacks', 'href' => route('panel.stacks.index')],
                ['label' => $stack->name, 'href' => route('panel.stacks.edit', ['stack' => $stack])],
                ['label' => 'Websites', 'href' => route('panel.stacks.edit.websites', ['stack' => $stack])],
            ],
            'tabs' => $this->getEditTabs($stack),
            'canEdit' => Gate::allows('update', $stack),
        ]);
    }

    /**
     * ------------------------------------------------------------
     * Stack Website Functionality
     * ------------------------------------------------------------
     */

    /**
     * Attach a website to the stack.
     */
    public function attachWebsite(AttachWebsiteRequest $request, Stack $stack)
    {
        $stack->websites()
            ->syncWithoutDetaching($request->validated()['website_id']);

        logger()->info('test', [
            'raw' => $request->validated(),
        ]);

        return redirect()->back();
    }

    /**
     * Detach a website from the stack.
     */
    public function detachWebsite(DetachWebsiteRequest $request, Stack $stack)
    {
        $stack->websites()
            ->detach($request->validated()['website_id']);

        return redirect()->back();
    }

    /**
     * ------------------------------------------------------------
     * Stack User Functionality
     * ------------------------------------------------------------
     */

    /**
     * Attach a user to the stack.
     */
    public function attachUser(AttachUserRequest $request, Stack $stack)
    {
        $user = User::query()
            ->whereNot('user_id', auth()->user()->getKey())
            ->whereNot('user_id', $stack->created_by_user_id)
            ->where('email', $request->validated()['email'])
            ->first();

        if ($user) {
            $stack->users()
                ->syncWithoutDetaching($user->getKey());
        }

        return redirect()->back();
    }

    /**
     * Detach a user from the stack.
     */
    public function detachUser(DetachUserRequest $request, Stack $stack)
    {
        $stack->users()
            ->detach($request->validated()['user_id']);

        return redirect()->back();
    }

    /**
     * Update a user's pivot data.
     */
    public function updateUser(UpdateUserRequest $request, Stack $stack)
    {
        $stack
            ->users()
            ->updateExistingPivot($request->validated()['user_id'], [
                'can_view' => $request->validated()['can_view'],
                'can_edit' => $request->validated()['can_edit'],
            ]);

        return redirect()->back();
    }

    /**
     * ------------------------------------------------------------
     * Stack Membership Functionality
     * ------------------------------------------------------------
     */

    /**
     * Leave a stack you are a member of.
     */
    public function leaveStack(LeaveStackRequest $request, Stack $stack)
    {
        $stack
            ->users()
            ->detach(auth()->user()->getKey());

        return redirect()->route('panel.stacks.index');
    }

    /**
     * Accept an invitation to the stack.
     */
    public function acceptStackInvite(AcceptStackInviteRequest $request, Stack $stack)
    {
        $stack
            ->users()
            ->updateExistingPivot(auth()->user()->getKey(), [
                'joined_at' => now(),
            ]);

        return redirect()->route('panel.stacks.edit', [
            'stack' => $stack,
        ]);
    }

    /**
     * Reject an invitation to the stack.
     */
    public function rejectStackInvite(RejectStackInviteRequest $request, Stack $stack)
    {
        $stack
            ->users()
            ->detach(auth()->user()->getKey());

        return redirect()->route('panel.stacks.index');
    }
}
