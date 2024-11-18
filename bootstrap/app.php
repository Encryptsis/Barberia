<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',  // Ruta a tus rutas web
        commands: __DIR__.'/../routes/console.php',  // Ruta a tus comandos
        health: '/up',  // Ruta para la verificación de estado
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(); // Registrar el grupo 'web' sin modificaciones

        // Registrar 'is_admin' como un alias de middleware de ruta
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Aquí puedes personalizar la gestión de excepciones si lo necesitas
    })
    ->create();
