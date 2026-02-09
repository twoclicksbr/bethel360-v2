<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->group('api', [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Middleware aliases
        $middleware->alias([
            'auth.api' => \App\Http\Middleware\AuthenticateApi::class,
            'resolve.person' => \App\Http\Middleware\ResolvePersonFromUser::class,
            'check.permission' => \App\Http\Middleware\CheckPermission::class,
            'ensure.active' => \App\Http\Middleware\EnsureActiveStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
