<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'account' => App\Http\Middleware\AccountMiddleware::class,
            'admin' => App\Http\Middleware\AdminMiddleware::class,
            'vendor' => App\Http\Middleware\VendorMiddleware::class,
            'customer' => App\Http\Middleware\CustomerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // A request body over PHP's post_max_size would otherwise surface as a
        // blank 413/419. This exception fires before the session starts, so
        // render a standalone page (no flash/back() available here).
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            return response()->view('errors.upload-too-large', [
                'limit' => ini_get('post_max_size'),
            ], 413);
        });
    })->create();
