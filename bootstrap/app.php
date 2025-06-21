<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        health: '/up',
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        // Web Middleware
        $middleware->web(append: [
            \Spatie\CookieConsent\CookieConsentMiddleware::class,
            \GemaDigital\Http\Middleware\ImpersonateMiddleware::class,
            \GemaDigital\Http\Middleware\AppLocale::class,
            \GemaDigital\Http\Middleware\ForceHttpsScheme::class,
        ]);

        // API Middleware
        $middleware->api(prepend: [
            'throttle:120,1',
            \GemaDigital\Http\Middleware\EnableQueryLog::class,
            \GemaDigital\Http\Middleware\AppLocale::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(\GemaDigital\Exceptions\ExceptionHandler::handle(...))
    ->create();
