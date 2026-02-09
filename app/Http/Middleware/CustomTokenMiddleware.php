<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        // Prefix handle karein (agar 1|... format ho)
        if (str_contains($token, '|')) {
            $token = explode('|', $token, 2)[1];
        }

        // Hashed token ke sath User dhoondein
        $hashedToken = hash('sha256', $token);
        $user = User::where('api_token', $hashedToken)->first();

        // Agar hash se nahi mila toh plain se check karein (Safe fallback)
        if (!$user) {
            $user = User::where('api_token', $token)->first();
        }

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        // USER KO LOGIN KARWANA LAZMI HAI
        auth()->login($user);

        return $next($request);
    }
}