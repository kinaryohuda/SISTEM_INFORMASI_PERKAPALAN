<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SuperAdminAccess
{
    /**
     * Izinkan hanya superAdmin.
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        return redirect()->route('superAdmin.dashboard')
            ->with('error', 'Akses ditolak: halaman ini hanya untuk superAdmin.');
    }
}
