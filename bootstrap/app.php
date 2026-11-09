<?php

use App\Http\Middleware\EnsureCorrelationId;
use App\Http\Middleware\EnsureUserRole;
use App\Http\Middleware\VerifyTurnstile;
use App\Logging\RedactSensitiveData;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(EnsureCorrelationId::class);
        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('admin/*')
            ? route('admin.login')
            : route('staff.login'));
        $middleware->alias([
            'turnstile' => VerifyTurnstile::class,
            'role' => EnsureUserRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
