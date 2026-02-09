<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workspace;

class WorkspacePolicy
{
    public function view(User $user, Workspace $workspace): bool
    {
        return $user->isAdmin()
            || $workspace->owner_id === $user->id
            || $workspace->members()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Workspace $workspace): bool
    {
        return $user->isAdmin() || $workspace->owner_id === $user->id;
    }

    public function delete(User $user, Workspace $workspace): bool
    {
        return $user->isAdmin() || $workspace->owner_id === $user->id;
    }
}
