<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function view(User $user, Message $message): bool
    {
        return $user->isAdmin()
            || $message->user_id === $user->id
            || $message->channel->members()->where('user_id', $user->id)->exists();
    }

    public function delete(User $user, Message $message): bool
    {
        return $user->isAdmin() || $message->user_id === $user->id;
    }
}
