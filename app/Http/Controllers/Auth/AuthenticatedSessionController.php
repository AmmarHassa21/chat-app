<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 1. User ko email se find karein
        $user = User::where('email', $request->email)->first();

        // 2. Password check karein
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credentials do not match our records.'],
            ]);
        }

        // 3. Naya Random Token generate karein
        $token = Str::random(80);

        // 4. Database mein hashed token save karein
        $user->update([
            'api_token' => hash('sha256', $token),
        ]);

        // 5. Response mein token bhej dein
        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // Token delete karne ke liye field ko null kar dein
            $user->update([
                'api_token' => null,
            ]);
        }

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}