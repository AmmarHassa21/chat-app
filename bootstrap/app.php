<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'validate.json'    => \App\Http\Middleware\JsonValidationMiddleware::class,
            'workspace.access'  => \App\Http\Middleware\WorkspaceMiddleware::class,
            'team.access'       => \App\Http\Middleware\TeamMiddleware::class,
            'channel.access'    => \App\Http\Middleware\ChannelMiddleware::class,
            'message.access'    => \App\Http\Middleware\MessageMiddleware::class,
            
            // File ka naam CustomTokenMiddleware hai isliye yahan wahi class use hogi
            'custom.auth'      => \App\Http\Middleware\CustomTokenMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();