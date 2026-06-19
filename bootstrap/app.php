<?php

use App\Http\Middleware\ResolveNiche;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Behind Render's load balancer — trust proxy headers so Laravel
        // generates correct https:// URLs and detects the client IP.
        $middleware->trustProxies(at: '*');

        // Resolve the active niche on every web request.
        // Currently config-driven; ready for URL/subdomain resolution later.
        $middleware->web(append: [
            ResolveNiche::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
