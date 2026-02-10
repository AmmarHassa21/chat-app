<?php

use Illuminate\Support\Facades\Route;

// Controllers Import
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

Route::middleware('validate.json')->group(function () {
    // Auth Basics
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    // Password Reset
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
    
    
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.reset');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Custom MongoDB Auth Required)
|--------------------------------------------------------------------------
*/

Route::middleware(['custom.auth', 'validate.json'])->group(function () {
    
    // 1. Workspaces CRUD
    Route::apiResource('workspaces', WorkspaceController::class);

    // 2. Teams CRUD
    Route::apiResource('teams', TeamController::class);

    // 3. Channels CRUD
    Route::apiResource('channels', ChannelController::class);

    // 4. Messages (Chatting)
    Route::get('channels/{channelId}/messages', [MessageController::class, 'index']);
    Route::post('channels/{channelId}/messages', [MessageController::class, 'store']);
    Route::apiResource('messages', MessageController::class)->except(['index', 'store']);

    // 5. User Profile & Logout
    Route::get('/user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });
    
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});