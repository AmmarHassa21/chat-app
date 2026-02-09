<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $message = $request->route('message');
        $user = $request->user();

        if (!$message || !$user) {
            return $next($request);
        }

        if ($user->can('view', $message)) {
            return $next($request);
        }

        return response()->json(['message' => 'Forbidden message access.'], 403);
    }
}
