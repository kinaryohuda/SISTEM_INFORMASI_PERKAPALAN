<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('main.admin.dashboard-admin', [
            'title' => 'Dashboard Admin',
            'user' => Auth::user()
        ]);
    }
}
