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
Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'dashboardAdmin'])->name('admin.dashboard');
    Route::post('/logout/admin', [LoginController::class, 'logoutAdmin'])->name('admin.logout');
    Route::get('dashboard/admin/user/level-user', [DashboardController::class, 'menuLevelUser'])->name('admin.levelUser');
    Route::get('dashboard/admin/user/level-user/create', [DashboardController::class, 'menuLevelUserCreate'])->name('admin.levelUser.create');
    Route::post('dashboard/admin/user/level-user/create', [DashboardController::class, 'menuLevelUserStore'])->name('admin.levelUser.store');
    Route::get('dashboard/admin/user/level-user/update/{id}', [DashboardController::class, 'menuLevelUserEdit'])->name('admin.levelUser.edit');
    Route::put('dashboard/admin/user/level-user/update/{id}', [DashboardController::class, 'menuLevelUserUpdate'])->name('admin.levelUser.update');
    Route::delete('dashboard/admin/user/level-user/delete/{id}', [DashboardController::class, 'menuLevelUserDelete'])->name('admin.levelUser.delete');
});

// Pegawai routes
Route::middleware('auth:pegawai')->group(function () {
    Route::get('/dashboard/pegawai', [DashboardController::class, 'dashboardPegawai'])->name('pegawai.dashboard');
    Route::post('/logout/pegawai', [LoginController::class, 'logoutPegawai'])->name('pegawai.logout');
});
