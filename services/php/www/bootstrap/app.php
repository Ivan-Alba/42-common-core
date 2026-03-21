<?php

use App\Exceptions\SocialException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        // EXCLUDE API ROUTES FROM CSRF PROTECTION
        // This allows Unity to make POST requests using Sanctum tokens without 419 errors
        $middleware->validateCsrfTokens(except: [
            'api/*', // This covers all routes in your api.php file
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (SocialException $e) {
            return response()->json(['error' => $e->getMessage()], $e->status());
        });
    })->create();
