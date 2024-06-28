<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Website;

class WebsitePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Website $website): bool
    {
        if ($website->created_by_user_id == $user->getKey()) {
            return true;
        }

        if (
            $website
                ->stacks()
                ->where('created_by_user_id', $user->getKey())
                ->orWhereHas('users', function ($subQuery) use ($user) {
                    $subQuery
                        ->orWhere('pivot_stacks_users.user_id', $user->getKey())
                        ->orWhere('pivot_stacks_users.can_view', true);
                })
                ->exists()
        ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Website $website): bool
    {
        if ($user->getKey() === $website->created_by_user_id) {
            return true;
        }

        if (
            $website
                ->stacks()
                ->where('created_by_user_id', $user->getKey())
                ->orWhereHas('users', function ($subQuery) use ($user) {
                    $subQuery
                        ->where('pivot_stacks_users.user_id', $user->getKey())
                        ->where('pivot_stacks_users.can_edit', true);
                })
                ->exists()
        ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Website $website): bool
    {
        return $user->getKey() === $website->created_by_user_id;
    }
}
