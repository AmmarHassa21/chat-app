<?php

namespace App\Policies;

use App\Models\Channel;
use App\Models\User;

class ChannelPolicy
{
    public function view(User $user, Channel $channel): bool
    {
        if ($user->isAdmin() || $channel->creator?->id === $user->id) {
            return true;
        }

        if ($channel->type === 'public') {
            return $channel->team->members()->where('user_id', $user->id)->exists();
        }

        return $channel->members()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Channel $channel): bool
    {
        return $user->isAdmin() || $channel->creator?->id === $user->id;
    }

    public function delete(User $user, Channel $channel): bool
    {
        return $user->isAdmin() || $channel->creator?->id === $user->id;
    }
}
