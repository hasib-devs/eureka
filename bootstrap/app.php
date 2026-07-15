<?php

declare(strict_types=1);

use App\Http\Middleware\AccountMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\VendorMiddleware;
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
            'account' => AccountMiddleware::class,
            'admin' => AdminMiddleware::class,
            'vendor' => VendorMiddleware::class,
            'customer' => CustomerMiddleware::class,
        ]);

        // Cookies written by JavaScript, not by Laravel. EncryptCookies tries to
        // decrypt every incoming cookie and replaces anything it cannot read
        // with null — silently. These are plaintext by definition (the pixel,
        // gtag and our consent banner set them client-side), so without this
        // exception list the server reads null for all of them:
        //   _fbp/_fbc      → the strongest Meta match signals; EMQ collapses
        //   _ga            → every server-side GA4 hit invents a second user
        //   tracking_consent → an EU visitor's consent never reaches the server,
        //                      so the banner reappears on every page forever
        // None of that raises an error, which is exactly why it needs pinning.
        $middleware->encryptCookies(except: [
            '_fbp',
            '_fbc',
            '_ga',
            'tracking_consent',
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
