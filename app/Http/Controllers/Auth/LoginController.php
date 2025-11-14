<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\LogLogin; // tambah import model log

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('main.auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input dengan custom message bahasa Indonesia
        $messages = [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
        ];

        $credentials = $request->validate([
            'email' => ['required', 'email:rfc'],
            'password' => ['required', 'string'],
        ], $messages);

        $remember = $request->boolean('remember'); // Ambil checkbox "Ingat saya"

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            LogLogin::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Jika "remember me" dicentang, set cookie selama 2 hari (2880 menit)
            if ($remember) {
                Cookie::queue(Auth::getRecallerName(), $user->getRememberToken(), 2880);
            }

            // Redirect sesuai role
            if ($user->role === 'superAdmin') {
                return redirect()->route('superAdmin.dashboard')
                    ->with('success', 'Selamat datang, Superadmin!');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard-admin')
                    ->with('success', 'Selamat datang, Admin!');
            } else {
                return redirect()->route('user.dashboard')
                    ->with('success', 'Login berhasil!');
            }
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah logout.');
    }
}
