<?php

namespace App\Providers;

use App\Models\Channel;
use App\Models\Message;
use App\Models\Team;
use App\Models\Workspace;
use App\Policies\ChannelPolicy;
use App\Policies\MessagePolicy;
use App\Policies\TeamPolicy;
use App\Policies\WorkspacePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Workspace::class => WorkspacePolicy::class,
        Team::class => TeamPolicy::class,
        Channel::class => ChannelPolicy::class,
        Message::class => MessagePolicy::class,
    ];

    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
