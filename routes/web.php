<?php

use App\Http\Controllers\ChannelController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('workspaces', WorkspaceController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('teams', TeamController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::apiResource('channels', ChannelController::class)->only(['store', 'show', 'update', 'destroy']);
    Route::post('messages', [MessageController::class, 'store'])->name('messages.store');
});
