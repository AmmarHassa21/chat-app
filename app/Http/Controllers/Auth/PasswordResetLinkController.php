<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 1. Manual User Check (Tension khatam)
        // MongoDB mein direct query karein
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'errors' => ['email' => ["We can't find a user with that email address."]]
            ], 422);
        }

        // 2. Laravel Broker ko sirf email bhejien notification ke liye
        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)], 200);
        }

        return response()->json([
            'errors' => ['email' => [__($status)]]
        ], 422);
    }
}