<?php

namespace App\Policies;

use App\Models\Stack;
use App\Models\User;

class StackPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Stack $stack): bool
    {
        if ($user->getKey() === $stack->created_by_user_id) {
            return true;
        }

        if ($stack->users()->where('pivot_stacks_users.user_id', $user->getKey())->exists()) {
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
    public function update(User $user, Stack $stack): bool
    {
        return $user->getKey() === $stack->created_by_user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Stack $stack): bool
    {
        return $user->getKey() === $stack->created_by_user_id;
    }

    // editWebsites
    // editMembers
    // editSettings

    // addWebsite     // AttachWebsiteRequest
    // removeWebsite  // DetachWebsiteRequest

    // inviteUser     // AttachUserRequest
    // removeUser     // RemoveUserRequest
    // updateUser     // UpdateUserRequest

    // leaveStack        // LeaveStackRequest
    // acceptStackInvite // AcceptStackInvite
    // rejectStackInvite // RejectStackInvite
}
