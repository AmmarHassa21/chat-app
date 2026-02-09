<?php

namespace App\Services;

use App\Models\Channel;
use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class ChatService
{
    public function sendMessage(User $user, Channel $channel, string $body): Message
    {
        if (! $this->canAccessChannel($user, $channel)) {
            throw new AuthorizationException('You may not post to this channel.');
        }

        return $channel->messages()->create([
            'user_id' => $user->id,
            'body' => $body,
        ]);
    }

    public function canAccessChannel(User $user, Channel $channel): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($channel->type === 'public') {
            return $channel->team
                ->members()
                ->where('user_id', $user->id)
                ->exists();
        }

        return $channel->members()
            ->where('users.id', $user->id)
            ->exists();
    }
}
