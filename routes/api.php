<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ChannelController;

// Auth Routes (Login/Register)
Route::middleware('validate.json')->group(function () {
    Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
    Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
});

// Protected Routes (MongoDB Custom Auth)
Route::middleware(['custom.auth', 'validate.json'])->group(function () {
    
    // WORKSPACES CRUD
    Route::apiResource('workspaces', WorkspaceController::class);

    // TEAMS (In Workspaces)
    Route::apiResource('teams', TeamController::class);

    // CHANNELS (In Teams/Workspaces)
    Route::apiResource('channels', ChannelController::class);

    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy']);
});