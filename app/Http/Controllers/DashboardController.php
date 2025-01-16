<?php

namespace App\Http\Controllers;

use App\Models\db_menu;
use App\Models\db_user_akses;
use App\Models\db_user_level;
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

    public function menuLevelUser()
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

        $userLevels = db_user_level::all();
        $dataAkses = db_user_akses::where('id_level', $levelAkses->id_level)->where('id_menu', 4)->get();

        return view('user.level user.index', compact('menus', 'userLevels', 'dataAkses'));
    }

    public function menuLevelUserCreate()
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

        return view('user.level user.add', compact('menus'));
    }

    public function menuLevelUserStore(Request $request)
    {
        $request->validate([
            'nama_level' => 'required',
            'status' => 'required|boolean',
            'hak_add' => 'array',
            'hak_edit' => 'array',
            'hak_delete' => 'array'
        ]);

        $level = db_user_level::create([
            'nama_level' => $request->nama_level,
            'status' => $request->status,
        ]);
        $id_level = $level->id_level;

        $allMenu = db_menu::all();
        foreach ($allMenu as $menu) {
            $id_menu = $menu->id_menu;

            // $melihat = isset($request->melihat[$id_menu]) ? 1 : 0;
            $hak_add = isset($request->hak_add[$id_menu]) ? 1 : 0;
            $hak_edit = isset($request->hak_edit[$id_menu]) ? 1 : 0;
            $hak_delete = isset($request->hak_delete[$id_menu]) ? 1 : 0;

            db_user_akses::create([
                'id_level' => $id_level,
                'id_menu' => $id_menu,
                // 'melihat' => $melihat,
                'hak_add' => $hak_add,
                'hak_edit' => $hak_edit,
                'hak_delete' => $hak_delete,
            ]);
        }

        $userId = Auth::guard('web')->id();

        $levelAkses = db_user_level_akses::with('level.userAkses.menu')
            ->where("id_user", $userId)
            ->first();
        $menus = $levelAkses->level->userAkses
            ->pluck('menu')
            ->flatten()
            ->where('sub_id_menu', 0)
            ->sortBy('urutan');

        $userLevels = db_user_level::all();

        return redirect()->route('admin.levelUser', compact('menus', 'userLevels'));
    }

    public function menuLevelUserEdit($id)
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

        $dataLevel = db_user_level::where('id_level', $id)->first();
        $dataAkses = db_user_akses::where('id_level', $id)->get()->keyBy('id_menu');
        return view('user.level user.edit', compact('menus', 'dataLevel', 'dataAkses'));
    }

    public function menuLevelUserUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_level' => 'required',
            'status' => 'required|boolean',
            'hak_add' => 'array',
            'hak_edit' => 'array',
            'hak_delete' => 'array'
        ]);

        $level = db_user_level::find($id);
        $level->update([
            'nama_level' => $request->nama_level,
            'status' => $request->status,
        ]);
        $allMenu = db_menu::all();
        foreach ($allMenu as $menu) {
            $id_menu = $menu->id_menu;

            // $melihat = isset($request->melihat[$id_menu]) ? 1 : 0;
            $hak_add = isset($request->hak_add[$id_menu]) ? 1 : 0;
            $hak_edit = isset($request->hak_edit[$id_menu]) ? 1 : 0;
            $hak_delete = isset($request->hak_delete[$id_menu]) ? 1 : 0;

            $akses = db_user_akses::where('id_level', $id)->where('id_menu', $id_menu)->first();
            $akses->update([
                'id_level' => $id,
                'id_menu' => $id_menu,
                // 'melihat' => $melihat,
                'hak_add' => $hak_add,
                'hak_edit' => $hak_edit,
                'hak_delete' => $hak_delete,
            ]);
        }

        $userId = Auth::guard('web')->id();

        $levelAkses = db_user_level_akses::with('level.userAkses.menu')
            ->where("id_user", $userId)
            ->first();
        $menus = $levelAkses->level->userAkses
            ->pluck('menu')
            ->flatten()
            ->where('sub_id_menu', 0)
            ->sortBy('urutan');

        $userLevels = db_user_level::all();

        return redirect()->route('admin.levelUser', compact('menus', 'userLevels'));
    }
    public function menuLevelUserDelete($id)
    {
        $allMenu = db_menu::all();
        foreach ($allMenu as $menu) {
            $id_menu = $menu->id_menu;
            $akses = db_user_akses::where('id_level', $id)->where('id_menu', $id_menu)->first();
            $akses->delete();
        }
        $level = db_user_level::find($id);
        $level->delete();

        $userId = Auth::guard('web')->id();

        $levelAkses = db_user_level_akses::with('level.userAkses.menu')
            ->where("id_user", $userId)
            ->first();
        $menus = $levelAkses->level->userAkses
            ->pluck('menu')
            ->flatten()
            ->where('sub_id_menu', 0)
            ->sortBy('urutan');

        $userLevels = db_user_level::all();

        return redirect()->route('admin.levelUser', compact('menus', 'userLevels'));
    }
}
