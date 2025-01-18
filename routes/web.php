<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});
// Admin routes
Route::middleware('auth:web,pegawai')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout/admin', [LoginController::class, 'logoutAdmin'])->name('admin.logout');
    Route::post('/logout/pegawai', [LoginController::class, 'logoutPegawai'])->name('pegawai.logout');
    Route::get('dashboard/user/level-user', [DashboardController::class, 'menuLevelUser'])->name('admin.levelUser');
    Route::get('dashboard/user/level-user/create', [DashboardController::class, 'menuLevelUserCreate'])->name('admin.levelUser.create');
    Route::post('dashboard/user/level-user/create', [DashboardController::class, 'menuLevelUserStore'])->name('admin.levelUser.store');
    Route::get('dashboard/user/level-user/update/{id}', [DashboardController::class, 'menuLevelUserEdit'])->name('admin.levelUser.edit');
    Route::put('dashboard/user/level-user/update/{id}', [DashboardController::class, 'menuLevelUserUpdate'])->name('admin.levelUser.update');
    Route::delete('dashboard/user/level-user/delete/{id}', [DashboardController::class, 'menuLevelUserDelete'])->name('admin.levelUser.delete');
});

// Pegawai routes
Route::middleware('auth:pegawai')->group(function () {
    Route::get('/dashboard/pegawai', [DashboardController::class, 'dashboardPegawai'])->name('pegawai.dashboard');
});
