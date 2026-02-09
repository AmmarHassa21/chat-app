<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChannelMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $channel = $request->route('channel');
        $user = $request->user();

        if (!$channel || !$user) {
            return $next($request);
        }

        if ($user->can('view', $channel)) {
            return $next($request);
        }

        return response()->json(['message' => 'Forbidden channel access.'], 403);
    }
}
