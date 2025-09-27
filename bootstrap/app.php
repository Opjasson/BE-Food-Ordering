<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'ableCreateOrder' => \App\Http\Middleware\ableCreateOrder::class,
            'ableFinishOrder' => \App\Http\Middleware\ableFinishOrder::class,
            'ableCreateUser' => \App\Http\Middleware\ableCreateUser::class,
            'ableCreateUpdateItem' => \App\Http\Middleware\ableCreateUpdateItem::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
