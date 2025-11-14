<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        // Daftar fitur yang sedang maintenance
        $maintenanceRoutes = [
            'forgot-password',
        ];

        // Cek apakah route cocok
        foreach ($maintenanceRoutes as $route) {
            if ($request->is($route) || $request->is($route . '/*')) {
                return response()->view('maintenance.maintenance');
            }
        }

        return $next($request);
    }
}
