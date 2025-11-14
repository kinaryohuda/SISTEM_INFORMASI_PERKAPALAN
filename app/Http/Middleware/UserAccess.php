<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserAccess
{
    /**
     * Izinkan hanya role user (bukan admin/superAdmin)
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role === 'user') {
            return $next($request);
        }

        // Jika admin/superAdmin mencoba masuk ke halaman user
        if ($user && $user->role === 'superAdmin') {
            return redirect()->route('superAdmin.dashboard')
                ->with('error', 'Akses ditolak: halaman ini hanya untuk user.');
        }

        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard-admin')
                ->with('error', 'Akses ditolak: halaman ini hanya untuk user.');
        }

        // Kalau belum login
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
}
