<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkspaceMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $workspace = $request->route('workspace');
        $user = $request->user();

        if (!$workspace || !$user) {
            return $next($request);
        }

        if ($user->can('view', $workspace)) {
            return $next($request);
        }

        return response()->json(['message' => 'Forbidden workspace access.'], 403);
    }
}
