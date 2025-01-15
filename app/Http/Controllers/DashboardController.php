<?php

namespace App\Http\Controllers;

use App\Models\db_user_level_akses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function dashboardAdmin()
    {
        $userId = Auth::guard('web')->id();

        $levelAkses = db_user_level_akses::with('level.userAkses.menu')
            ->where("id_user", $userId)
            ->first();
        $menus = $levelAkses->level->userAkses
            ->pluck('menu')
            ->flatten()
            ->where('sub_id_menu', 0)
            ->sortBy('urutan');

        return view('dashboard.admin.index', compact('menus'));
    }
    public function dashboardPegawai()
    {
        $pegawaiId = Auth::guard('pegawai')->id();

        $levelAkses = db_user_level_akses::with('level.userAkses.menu')
            ->where("id_pegawai", $pegawaiId)
            ->first();
        $menus = $levelAkses->level->userAkses
            ->pluck('menu')
            ->flatten()
            ->where('sub_id_menu', 0)
            ->sortBy('urutan');
        return view('dashboard.pegawai.index', compact('menus'));
    }
}
