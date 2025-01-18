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

    function getMenuSidebar($guard, $level)
    {
        $userId = Auth::guard($guard)->id();

        // Ambil level akses berdasarkan user yang login
        $levelAkses = db_user_level_akses::with('level.userAkses.menu')
            ->where("id_{$level}", $userId)
            ->first();

        if (!$levelAkses) {
            return [
                'menus' => collect(),
                'menuIds' => []
            ];
        }

        // Ambil ID menu yang diakses user
        $userAccessMenus = $levelAkses->level->userAkses
            ->pluck('menu')
            ->flatten()
            ->pluck('id_menu')
            ->toArray();

        // Ambil submenu dan parent menu terkait
        $subMenus = db_menu::whereIn('id_menu', $userAccessMenus)
            ->where('sub_id_menu', '!=', 0)
            ->get();

        $parentMenus = db_menu::whereIn('id_menu', $subMenus->pluck('sub_id_menu'))
            ->pluck('id_menu')
            ->toArray();

        // Gabungkan semua ID menu
        $allMenus = collect(array_merge($userAccessMenus, $parentMenus))
            ->unique()
            ->sort()
            ->values();

        // Ambil data menu berdasarkan ID dan sub_id_menu = 0
        $menus = db_menu::whereIn('id_menu', $allMenus)
            ->where('sub_id_menu', 0)
            ->orderBy('urutan')
            ->get();

        return [
            'menus' => $menus,
            'menuIds' => $allMenus,
            'levelAkses' => $levelAkses
        ];
    }

    public function dashboard()
    {
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
            $menus = $sidebar['menus'];
            $menuIds = $sidebar['menuIds'];

            return view('dashboard.admin.index', compact('menus', 'menuIds'));
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
            $menus = $sidebar['menus'];
            $menuIds = $sidebar['menuIds'];
            return view('dashboard.pegawai.index', compact('menus', 'menuIds'));
        }

        return redirect()->route('login');
    }

    public function menuLevelUser()
    {
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];
        $levelAkses = $sidebar['levelAkses'];
        $userLevels = db_user_level::all();
        $dataAkses = db_user_akses::where('id_level', $levelAkses->id_level)->where('id_menu', 4)->get();

        return view('user.level user.index', compact('menus', 'menuIds', 'userLevels', 'dataAkses'));
    }

    public function menuLevelUserCreate()
    {
        $sidebar = $this->getMenuSidebar('web', 'user');
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        return view('user.level user.add', compact('menus', 'menuIds'));
    }

    public function menuLevelUserStore(Request $request)
    {
        $request->validate([
            'nama_level' => 'required',
            'status' => 'required|boolean',
            'melihat' => 'array',
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

            $melihat = isset($request->melihat[$id_menu]) ? 1 : 0;
            $hak_add = isset($request->hak_add[$id_menu]) ? 1 : 0;
            $hak_edit = isset($request->hak_edit[$id_menu]) ? 1 : 0;
            $hak_delete = isset($request->hak_delete[$id_menu]) ? 1 : 0;

            if ($melihat) {
                db_user_akses::create([
                    'id_level' => $id_level,
                    'id_menu' => $id_menu,
                    // 'melihat' => $melihat,
                    'hak_add' => $hak_add,
                    'hak_edit' => $hak_edit,
                    'hak_delete' => $hak_delete,
                ]);
            }
        }

        $sidebar = $this->getMenuSidebar('web', 'user');
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $userLevels = db_user_level::all();

        return redirect()->route('admin.levelUser', compact('menus', 'menuIds', 'userLevels'));
    }

    public function menuLevelUserEdit($id)
    {
        $sidebar = $this->getMenuSidebar('web', 'user');
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $dataLevel = db_user_level::where('id_level', $id)->first();
        $dataAkses = db_user_akses::where('id_level', $id)->get()->keyBy('id_menu');
        return view('user.level user.edit', compact('menus', 'menuIds', 'dataLevel', 'dataAkses'));
    }

    public function menuLevelUserUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_level' => 'required',
            'status' => 'required|boolean',
            'melihat' => 'array',
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

            $melihat = isset($request->melihat[$id_menu]) ? 1 : 0;

            $hak_add = $melihat && isset($request->hak_add[$id_menu]) ? 1 : 0;
            $hak_edit = $melihat && isset($request->hak_edit[$id_menu]) ? 1 : 0;
            $hak_delete = $melihat && isset($request->hak_delete[$id_menu]) ? 1 : 0;

            $akses = db_user_akses::where('id_level', $id)->where('id_menu', $id_menu)->first();

            if ($melihat) {
                if ($akses) {
                    $akses->update([
                        'id_level' => $id,
                        'id_menu' => $id_menu,
                        'hak_add' => $hak_add,
                        'hak_edit' => $hak_edit,
                        'hak_delete' => $hak_delete,
                    ]);
                } else {
                    db_user_akses::create([
                        'id_level' => $id,
                        'id_menu' => $id_menu,
                        'hak_add' => $hak_add,
                        'hak_edit' => $hak_edit,
                        'hak_delete' => $hak_delete,
                    ]);
                }
            } elseif ($akses) {
                $akses->delete();
            }
        }

        $sidebar = $this->getMenuSidebar('web', 'user');
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $userLevels = db_user_level::all();

        return redirect()->route('admin.levelUser', compact('menus', 'menuIds', 'userLevels'));
    }
    public function menuLevelUserDelete($id)
    {
        $allMenu = db_menu::all();
        foreach ($allMenu as $menu) {
            $id_menu = $menu->id_menu;
            $akses = db_user_akses::where('id_level', $id)->where('id_menu', $id_menu)->first();
            if ($akses) {
                $akses->delete();
            }
        }
        $level = db_user_level::find($id);
        $level->delete();

        $sidebar = $this->getMenuSidebar('web', 'user');
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $userLevels = db_user_level::all();

        return redirect()->route('admin.levelUser', compact('menus', 'menuIds', 'userLevels'));
    }
}
