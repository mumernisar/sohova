<?php

/*
|------------------------------------------------------------------------------
| SNIPPET — not a full file.
|------------------------------------------------------------------------------
| In Laravel 11/12 middleware is registered in bootstrap/app.php.
| Open your existing bootstrap/app.php and add the alias inside the
| ->withMiddleware(...) closure, as shown below.
*/

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',   // make sure the api route file is registered
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust all proxies (ngrok, etc.) so X-Forwarded-* headers
        // are used to detect the correct scheme and host automatically.
        $middleware->trustProxies(at: '*');

        // 👇 add this line
        $middleware->alias([
            'lead.token' => \App\Http\Middleware\EnsureApiToken::class,
        ]);
    })
    ->withExceptions(function ($exceptions) {
        //
    })->create();
