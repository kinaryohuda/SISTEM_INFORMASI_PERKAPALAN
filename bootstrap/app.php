<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /**
         *  Global middleware (berlaku untuk semua request)
         * Bisa kamu daftarkan di sini jika nanti butuh, contoh:
         * $middleware->use([ \App\Http\Middleware\SomeGlobalMiddleware::class ]);
         */

        /**
         *  Route Middleware (alias)
         * Middleware ini bisa dipanggil di file routes/web.php seperti:
         * ->middleware('admin')
         */
        $middleware->alias([
            'user' => \App\Http\Middleware\UserAccess::class,
            'admin' => \App\Http\Middleware\AdminAccess::class,
            'superAdmin' => \App\Http\Middleware\SuperAdminAccess::class,
            'maintenance' => \App\Http\Middleware\MaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        /**
         * Tempat untuk kustomisasi penanganan error
         * Misal, kamu bisa log error tertentu atau ubah tampilan error default.
         *
         * Contoh:
         * $exceptions->render(function (ModelNotFoundException $e, $request) {
         *     return response()->view('errors.not-found', [], 404);
         * });
         */
    })
    ->create();
