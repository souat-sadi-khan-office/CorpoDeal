<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'checkPermission' => \App\Http\Middleware\CheckPermission::class,
            'isAdmin' => \App\Http\Middleware\AdminAuth::class,
            'isCustomer' => \App\Http\Middleware\CustomerAuth::class,
            'ipSession' => \App\Http\Middleware\IPSessionMiddlewire::class,
            'email.verify' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'sslCommerzCsrf' => \App\Http\Middleware\SslCommerzMiddleware::class

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            if ($exception->getStatusCode() == 404) {
                return response()->view("errors.404", [], 404);
            }
        });
    })->create();
