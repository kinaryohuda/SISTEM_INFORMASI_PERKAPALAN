<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminAdminManagementController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardManagementController;
use App\Http\Controllers\SuperAdmin\SuperAdminKapalManagementController;
use App\Http\Controllers\SuperAdmin\SuperAdminKomponenPengajuanController;
use App\Http\Controllers\SuperAdmin\SuperAdminPengajuanPermohonanManagementController;
use App\Http\Controllers\SuperAdmin\SuperAdminRiwayatManagementController;
use App\Http\Controllers\SuperAdmin\SuperAdminUserManagementController;
use App\Http\Controllers\SuperAdmin\SuperAdminVerifikatorController;
use App\Http\Controllers\User\UserKapalController;
use App\Http\Controllers\User\UserPengajuanPermohonan;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\UserRiwayatController;
use App\Models\KomponenPengajuan;

// Landing Page
Route::get('/', function () {
    return view('main.public.landing-page');
})->name('landing');

// 🔐 AUTH ROUTES

// Login
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login')->middleware('guest');
    Route::post('/login', 'login')->name('login.process');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});

// Register
Route::controller(RegisteredUserController::class)->group(function () {
    Route::get('/register', 'create')->name('register')->middleware('guest');
    Route::post('/register', 'store')->name('register.store');
});

// ALL 
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile-user');
    Route::post('/profile/update', [UserProfileController::class, 'updateProfile'])->name('user.updateProfile');
    Route::post('/profile/change-password', [UserProfileController::class, 'changePassword'])->name('user.changePassword');
});



// USER 
Route::middleware(['auth', 'user'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // Pengajuan Permohonan 
    Route::get('/pengajuan-permohonan/create', [UserPengajuanPermohonan::class, 'index'])->name('user.pengajuan-permohonan-index');
    Route::post('/pengajuan-permohonan/store', [UserPengajuanPermohonan::class, 'store'])->name('user.pengajuan-permohonan-store');

    // Kapal 
    Route::get('/kapal', [UserKapalController::class, 'myKapal'])->name('user.kapal-index');
    Route::get('/kapal/create', [UserKapalController::class, 'create'])->name('user.kapal-create');
    Route::post('/kapal/post', [UserKapalController::class, 'store'])->name('user.kapal-store');
    Route::get('/kapal/{id_kapal}', [UserKapalController::class, 'viewDetail'])->name('user.kapal-viewDetail');
    Route::get('/kapal/{id_kapal}/edit', [UserKapalController::class, 'edit'])->name('user.kapal-edit');
    Route::put('/kapal/{id_kapal}/update', [UserKapalController::class, 'update'])->name('user.kapal-update');
    Route::delete('/kapal/{kapal}', [UserKapalController::class, 'destroy'])->name('user.kapal-destroy');



    Route::get('/riwayat', [UserRiwayatController::class, 'index'])->name('user.riwayat-index');
});

// ADMIN DASHBOARD
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard-admin');
    });

// SUPERADMIN DASHBOARD
Route::middleware(['auth', 'superAdmin'])
    ->prefix('superAdmin')
    ->name('superAdmin.')
    ->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardManagementController::class, 'index'])->name('dashboard');

        // Verivikasi 
        Route::get('verifikasi', [SuperAdminVerifikatorController::class, 'index'])->name('verifikasi.index');
        Route::get('verifikasi/{id_pengajuan}', [SuperAdminVerifikatorController::class, 'show'])->name('verifikasi.show');
        Route::post('verifikasi/{id_pengajuan}/status', [SuperAdminVerifikatorController::class, 'updateStatus'])->name('verifikasi.updateStatus');

        // PENGAJUAN PERMOHONAN 
        Route::get('/pengajuan-permohonan/create', [SuperAdminPengajuanPermohonanManagementController::class, 'index'])->name('pengajuan-permohonan-index');
        Route::post('/pengajuan-permohonan/store', [SuperAdminPengajuanPermohonanManagementController::class, 'store'])->name('pengajuan-permohonan-store');
        // Ajax load kapal berdasarkan user
        Route::get('/pengajuan-permohonan/load-kapal/{id_user}', [SuperAdminPengajuanPermohonanManagementController::class, 'loadKapal'])->name('load-kapal');
        Route::get('/user-data/{id}', [SuperAdminPengajuanPermohonanManagementController::class, 'getUserData']);

        // RIWAYAT
        Route::get('/riwayat', [SuperAdminRiwayatManagementController::class, 'index'])->name('riwayat-index');
        // KAPAL 
        Route::get('/kapal', [SuperAdminKapalManagementController::class, 'index'])->name('kapal-index');
        // Route::get('/kapal/{id_kapal}/show',[SuperAdminKapalManagementController::class, 'show'] )->name('kapal-show');
        // Route::get('/kapal/create',[SuperAdminKapalManagementController::class, 'create'] )->name('kapal-create');
        // Route::post('/kapal/store',[SuperAdminKapalManagementController::class, 'store'] )->name('kapal-store');
        // Route::get('/kapal/{id_kapal}/edit',[SuperAdminKapalManagementController::class, 'edit'])->name('kapal-edit');
        // Route::put('/kapal/{id_kapal}/update',[SuperAdminKapalManagementController::class, 'update'] )->name('kapal-update');
        // Route::delete('/kapal/{id_kapal}/delete', [SuperAdminKapalManagementController::class, 'destroy'])->name('kapal-delete');

        // USER BIASA
        Route::get('/pengguna-users', [SuperAdminUserManagementController::class, 'index'])->name('pengguna-user-index');
        Route::get('/pengguna-users/{id}show', [SuperAdminUserManagementController::class, 'show'])->name('pengguna-user-show');
        Route::get('/pengguna-users/create', [SuperAdminUserManagementController::class, 'create'])->name('pengguna-user-create');
        Route::post('/pengguna-users/store', [SuperAdminUserManagementController::class, 'store'])->name('pengguna-user-store');
        Route::get('/pengguna-users/{id}/edit', [SuperAdminUserManagementController::class, 'edit'])->name('pengguna-user-edit');
        Route::put('/pengguna-users/{id}', [SuperAdminUserManagementController::class, 'update'])->name('pengguna-user-update');
        Route::delete('/pengguna-users/{id}', [SuperAdminUserManagementController::class, 'destroy'])->name('pengguna-user-delete');

        // ADMIN
        Route::get('/pengguna-admins', [SuperAdminAdminManagementController::class, 'index'])->name('pengguna-admin-index');
        Route::get('/pengguna-admins/create', [SuperAdminAdminManagementController::class, 'create'])->name('pengguna-admin-create');
        Route::post('/pengguna-admins/store', [SuperAdminAdminManagementController::class, 'store'])->name('pengguna-admin-store');
        Route::get('/pengguna-admins/{id}/edit', [SuperAdminAdminManagementController::class, 'edit'])->name('pengguna-admin-edit');
        Route::put('/pengguna-admins/{id}', [SuperAdminAdminManagementController::class, 'update'])->name('pengguna-admin-update');
        Route::delete('/pengguna-admins/{id}', [SuperAdminAdminManagementController::class, 'destroy'])->name('pengguna-admin-delete');

        // KomponenPengajuan
        Route::get('/komponen-pengajuan', [SuperAdminKomponenPengajuanController::class, 'index'])->name('komponen-pengajuan-index');
        Route::get('/komponen-pengajuan/create', [SuperAdminKomponenPengajuanController::class, 'create'])->name('komponen-pengajuan-create');
        Route::post('/komponen-pengajuan/store', [SuperAdminKomponenPengajuanController::class, 'store'])->name('komponen-pengajuan-store');
        Route::get('/komponen-pengajuan/{id_komponen_pengajuan_pengajuan}/edit', [SuperAdminKomponenPengajuanController::class, 'edit'])->name('komponen-pengajuan-edit');
        Route::put('/komponen-pengajuan/{id_komponen_pengajuan_pengajuan}/update', [SuperAdminKomponenPengajuanController::class, 'update'])->name('komponen-pengajuan-update');
    });




// MAINTENANCE
// Forgot Password
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'showForgotForm')
        ->name('password.request')
        ->middleware('maintenance'); // pakai middleware maintenance
    Route::post('/forgot-password', 'sendResetLinkEmail')
        ->name('password.email')
        ->middleware('maintenance');
});
