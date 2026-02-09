<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $team = $request->route('team');
        $user = $request->user();

        if (!$team || !$user) {
            return $next($request);
        }

        if ($user->can('view', $team)) {
            return $next($request);
        }

        return response()->json(['message' => 'Forbidden team access.'], 403);
    }
}
