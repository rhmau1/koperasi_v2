<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InputUserController;

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
Route::middleware('auth:web,pegawai,anggota')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout/admin', [LoginController::class, 'logoutAdmin'])->name('admin.logout');
    Route::post('/logout/pegawai', [LoginController::class, 'logoutPegawai'])->name('pegawai.logout');
    Route::post('/logout/anggota', [LoginController::class, 'logoutAnggota'])->name('anggota.logout');
    Route::get('pilih-role', [LoginController::class, 'pilihRole'])->name('pilihRole');
    Route::put('pilih-role/{id}', [LoginController::class, 'pilihRoleUpdate'])->name('pilihRole.update');
    Route::get('dashboard/level', [DashboardController::class, 'level'])->name('level');
    Route::post('dashboard/level', [DashboardController::class, 'levelStore'])->name('level.store');
    Route::delete('dashboard/level/{id}', [DashboardController::class, 'levelDelete'])->name('level.delete');

    Route::get('dashboard/user/input-user', [DashboardController::class, 'inputUser'])->name('inputUser');
    Route::get('dashboard/user/input-user/create', [DashboardController::class, 'inputUserCreate'])->name('inputUser.create');
    Route::post('dashboard/user/input-user/create', [DashboardController::class, 'inputUserStore'])->name('inputUser.store');
    Route::get('dashboard/user/input-user/update/{id}', [DashboardController::class, 'inputUserEdit'])->name('inputUser.edit');
    Route::put('dashboard/user/input-user/update/{id}', [DashboardController::class, 'inputUserUpdate'])->name('inputUser.update');
    Route::delete('dashboard/user/input-user/delete/{id}', [DashboardController::class, 'inputUserDelete'])->name('inputUser.delete');

    Route::get('dashboard/pegawai', [DashboardController::class, 'inputPegawai'])->name('inputPegawai');
    Route::get('dashboard/pegawai/create', [DashboardController::class, 'inputPegawaiCreate'])->name('inputPegawai.create');
    Route::post('dashboard/pegawai/create', [DashboardController::class, 'inputPegawaiStore'])->name('inputPegawai.store');
    Route::get('dashboard/pegawai/update/{id}', [DashboardController::class, 'inputPegawaiEdit'])->name('inputPegawai.edit');
    Route::put('dashboard/pegawai/update/{id}', [DashboardController::class, 'inputPegawaiUpdate'])->name('inputPegawai.update');
    Route::delete('dashboard/pegawai/delete/{id}', [DashboardController::class, 'inputPegawaiDelete'])->name('inputPegawai.delete');

    Route::get('dashboard/anggota', [DashboardController::class, 'inputAnggota'])->name('inputAnggota');
    Route::get('dashboard/anggota/create', [DashboardController::class, 'inputAnggotaCreate'])->name('inputAnggota.create');
    Route::post('dashboard/anggota/create', [DashboardController::class, 'inputAnggotaStore'])->name('inputAnggota.store');
    Route::get('dashboard/anggota/update/{id}', [DashboardController::class, 'inputAnggotaEdit'])->name('inputAnggota.edit');
    Route::put('dashboard/anggota/update/{id}', [DashboardController::class, 'inputAnggotaUpdate'])->name('inputAnggota.update');
    Route::delete('dashboard/anggota/delete/{id}', [DashboardController::class, 'inputAnggotaDelete'])->name('inputAnggota.delete');

    Route::get('dashboard/user/level-user', [DashboardController::class, 'menuLevelUser'])->name('levelUser');
    Route::get('dashboard/user/level-user/create', [DashboardController::class, 'menuLevelUserCreate'])->name('levelUser.create');
    Route::post('dashboard/user/level-user/create', [DashboardController::class, 'menuLevelUserStore'])->name('levelUser.store');
    Route::get('dashboard/user/level-user/update/{id}', [DashboardController::class, 'menuLevelUserEdit'])->name('levelUser.edit');
    Route::put('dashboard/user/level-user/update/{id}', [DashboardController::class, 'menuLevelUserUpdate'])->name('levelUser.update');
    Route::delete('dashboard/user/level-user/delete/{id}', [DashboardController::class, 'menuLevelUserDelete'])->name('levelUser.delete');
});
