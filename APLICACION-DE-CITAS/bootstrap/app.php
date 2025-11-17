<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // AÑADIDO: Registro del alias 'role' para el sistema RBAC
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserRole::class, 
        ]);
        
        // Puedes añadir aquí otros middlewares si es necesario
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();