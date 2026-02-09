<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function view(User $user, Team $team): bool
    {
        return $user->isAdmin()
            || $team->owner_id === $user->id
            || $team->workspace->members()->where('user_id', $user->id)->exists()
            || $team->members()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Team $team): bool
    {
        return $user->isAdmin() || $team->owner_id === $user->id;
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->isAdmin() || $team->owner_id === $user->id;
    }
}
