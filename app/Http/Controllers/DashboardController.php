<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\db_pegawai;
use App\Models\db_anggota;
use App\Models\db_menu;
use Illuminate\Http\Request;
use App\Models\db_user_akses;
use App\Models\db_user_level;
use App\Models\db_user_level_akses;
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
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
            $menus = $sidebar['menus'];
            $menuIds = $sidebar['menuIds'];
            return view('dashboard.anggota.index', compact('menus', 'menuIds'));
        }

        return redirect()->route('login');
    }

    public function inputUser()
    {
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];
        $levelAkses = $sidebar['levelAkses'];
        $dataAkses = db_user_akses::where('id_level', $levelAkses->id_level)->where('id_menu', 3)->get();

        $dataUsers = User::all();
        return view('user.user admin.index', compact('menus', 'menuIds', 'dataUsers', 'dataAkses'));
    }

    public function inputUserCreate()
    {
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        return view('user.user admin.add', compact('menus', 'menuIds'));
    }

    public function inputUserStore(Request $request)
    {
        $request->validate([
            'nama_user' => 'required',
            'email_user' => 'required|email',
            'password_user' => 'required|min:8',
            'status' => 'required|boolean',
            'hp_user' => 'required|numeric',
        ]);
        $user = User::create([
            'nama_user' => $request->nama_user,
            'email_user' => $request->email_user,
            'hp_user' => $request->hp_user,
            'password_user' => bcrypt($request->password_user),
            'status' => $request->status,
        ]);
        $id_user = $user->id_user;
        $levelAkses = db_user_level_akses::create([
            'id_user' => $id_user,
            'jenis_user' => 2,
            'id_level' => 2,
            'status' => $request->status,
            'id_pegawai' => 0,
            'id_anggota' => 0
        ]);

        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];
        $dataUsers = User::all();

        return redirect()->route('inputUser', compact('menus', 'menuIds', 'dataUsers'));
    }

    public function inputUserEdit($id)
    {
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $user = User::find($id);

        return view('user.user admin.edit', compact('menus', 'menuIds', 'user'));
    }

    public function inputUserUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_user' => 'required',
            'email_user' => 'required|email',
            'password_user' => 'required|min:8',
            'status' => 'required|boolean',
            'hp_user' => 'required|numeric',
        ]);

        $user = User::find($id);
        $user->update([
            'nama_user' => $request->nama_user,
            'email_user' => $request->email_user,
            'hp_user' => $request->hp_user,
            'password_user' => bcrypt($request->password_user),
            'status' => $request->status,
        ]);
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $dataUsers = User::all();

        return redirect()->route('inputUser', compact('menus', 'menuIds', 'dataUsers'));
    }

    public function roleUser($id)
    {
        $levelAkses = db_user_level_akses::where("id_user", $id)->get();
        $levelIds = $levelAkses->pluck('id_level')->toArray();
        $guard = '';
        $level = '';
        if (Auth::guard('web')->check()) {
            $guard = 'web';
            $level = 'user';
        } elseif (Auth::guard('pegawai')->check()) {
            $guard = 'pegawai';
            $level = 'pegawai';
        } elseif (Auth::guard('anggota')->check()) {
            $guard = 'anggota';
            $level = 'anggota';
        }
        $sidebar = $this->getMenuSidebar($guard, $level);
        $allLevels = db_user_level::whereNotIn('id_level', $levelIds)->whereNotIn('id_level', [3, 4])->get();
        $userLevels = db_user_level_akses::where("id_user", $id)->whereIn('id_level', $levelIds)->get();
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];
        $userId = $id;
        return view('user.user admin.role', compact('menus', 'menuIds', 'userLevels', 'allLevels', 'userId'));
    }

    public function roleUserStore(Request $request, $id)
    {
        $guard = '';
        $level = '';
        if (Auth::guard('web')->check()) {
            $guard = 'web';
            $level = 'user';
        } elseif (Auth::guard('pegawai')->check()) {
            $guard = 'pegawai';
            $level = 'pegawai';
        } elseif (Auth::guard('anggota')->check()) {
            $guard = 'anggota';
            $level = 'anggota';
        }
        $sidebar = $this->getMenuSidebar($guard, $level);
        $levelAkses = db_user_level_akses::where("id_user", $id)->get();

        db_user_level_akses::create([
            'id_pegawai' => 0,
            'jenis_user' => $request->id_level,
            'id_level' => $request->id_level,
            'status' => 0,
            'id_user' => $id,
            'id_anggota' => 0
        ]);
        $levelIds = $levelAkses->pluck('id_level')->toArray();
        $allLevels = db_user_level::whereNotIn('id_level', $levelIds)->get();
        $userLevels = db_user_level_akses::where("id_user", $id)->whereIn('id_level', $levelIds)->get();
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        return redirect()->route('roleUser', compact('menus', 'menuIds', 'userLevels', 'allLevels', 'id'));
    }


    public function roleUserDelete(Request $request, $id)
    {
        $guard = '';
        $level = '';
        if (Auth::guard('web')->check()) {
            $guard = 'web';
            $level = 'user';
        } elseif (Auth::guard('pegawai')->check()) {
            $guard = 'pegawai';
            $level = 'pegawai';
        } elseif (Auth::guard('anggota')->check()) {
            $guard = 'anggota';
            $level = 'anggota';
        }
        $sidebar = $this->getMenuSidebar($guard, $level);
        $levelId = $request->id_level;
        $levelAkses = db_user_level_akses::where("id_user", $id)->get();
        $levelIds = $levelAkses->pluck('id_level')->toArray();
        $allLevels = db_user_level::whereNotIn('id_level', $levelIds)->get();
        $userLevels = db_user_level_akses::where("id_user", $id)->whereIn('id_level', $levelIds)->get();
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $dataRole = db_user_level_akses::where('id_level', $levelId)->where('id_user', $id)->first();
        $dataRole->delete();

        return redirect()->route('roleUser', compact('menus', 'menuIds', 'userLevels', 'allLevels', 'id'));
    }

    public function inputUserDelete($id)
    {
        $user = User::find($id);
        $levelAkses = db_user_level_akses::where('id_user', $id)->first();
        if ($levelAkses) {
            $levelAkses->delete();
        }
        $user->delete();

        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $dataUsers = User::all();
        return redirect()->route('inputUser', compact('menus', 'menuIds', 'dataUsers'));
    }

    public function menuLevelUser()
    {
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
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
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];
        $allMenu = db_menu::all()->where('sub_id_menu', 0);

        return view('user.level user.add', compact('menus', 'menuIds', 'allMenu'));
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

        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $userLevels = db_user_level::all();

        return redirect()->route('levelUser', compact('menus', 'menuIds', 'userLevels'));
    }

    public function menuLevelUserEdit($id)
    {
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];
        $allMenu = db_menu::all()->where('sub_id_menu', 0);

        $dataLevel = db_user_level::where('id_level', $id)->first();
        $dataAkses = db_user_akses::where('id_level', $id)->get()->keyBy('id_menu');
        return view('user.level user.edit', compact('menus', 'menuIds', 'dataLevel', 'dataAkses', 'allMenu'));
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

        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $userLevels = db_user_level::all();

        return redirect()->route('levelUser', compact('menus', 'menuIds', 'userLevels'));
    }
    public function menuLevelUserDelete($id)
    {
        if ($id < 1 || $id > 4) {
            $allMenu = db_menu::all();
            foreach ($allMenu as $menu) {
                $id_menu = $menu->id_menu;
                $akses = db_user_akses::where('id_level', $id)->where('id_menu', $id_menu)->first();
                if ($akses) {
                    $akses->delete();
                }
                $levelAkses = db_user_level_akses::where('id_level', $id)->get();
                foreach ($levelAkses as $levelAkses) {
                    $levelAkses->delete();
                }
            }
            $level = db_user_level::find($id);
            $level->delete();
        }
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $userLevels = db_user_level::all();

        return redirect()->route('levelUser', compact('menus', 'menuIds', 'userLevels'));
    }

    public function inputPegawai()
    {
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];
        $levelAkses = $sidebar['levelAkses'];
        $dataAkses = db_user_akses::where('id_level', $levelAkses->id_level)->where('id_menu', 6)->get();

        $dataPegawai = db_pegawai::all();
        return view('pegawai.index', compact('menus', 'menuIds', 'dataPegawai', 'dataAkses'));
    }

    public function inputPegawaiCreate()
    {
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        return view('pegawai.add', compact('menus', 'menuIds'));
    }

    public function inputPegawaiStore(Request $request)
    {
        $request->validate([
            'nama_pegawai' => 'required',
            'email_pegawai' => 'required|email',
            'password_pegawai' => 'required|min:8',
            'status' => 'required|boolean',
            'hp_pegawai' => 'required|numeric',
        ]);
        $pegawai = db_pegawai::create([
            'nama_pegawai' => $request->nama_pegawai,
            'email_pegawai' => $request->email_pegawai,
            'hp_pegawai' => $request->hp_pegawai,
            'password_pegawai' => bcrypt($request->password_pegawai),
            'status' => $request->status,
        ]);
        $id_pegawai = $pegawai->id_pegawai;
        $levelAkses = db_user_level_akses::create([
            'id_pegawai' => $id_pegawai,
            'jenis_user' => 3,
            'id_level' => 3,
            'status' => $request->status,
            'id_user' => 0,
            'id_anggota' => 0
        ]);

        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];
        $dataPegawai = db_pegawai::all();

        return redirect()->route('inputPegawai', compact('menus', 'menuIds', 'dataPegawai'));
    }

    public function inputPegawaiEdit($id)
    {
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $pegawai = db_pegawai::find($id);

        return view('pegawai.edit', compact('menus', 'menuIds', 'pegawai'));
    }

    public function inputPegawaiUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_pegawai' => 'required',
            'email_pegawai' => 'required|email',
            'password_pegawai' => 'required|min:8',
            'status' => 'required|boolean',
            'hp_pegawai' => 'required|numeric',
        ]);

        $pegawai = db_pegawai::find($id);
        $pegawai->update([
            'nama_pegawai' => $request->nama_pegawai,
            'email_pegawai' => $request->email_pegawai,
            'hp_pegawai' => $request->hp_pegawai,
            'password_pegawai' => bcrypt($request->password_pegawai),
            'status' => $request->status,
        ]);
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $dataPegawai = db_pegawai::all();

        return redirect()->route('inputPegawai', compact('menus', 'menuIds', 'dataPegawai'));
    }

    public function rolePegawai($id)
    {
        $levelAkses = db_user_level_akses::where("id_pegawai", $id)->get();
        $levelIds = $levelAkses->pluck('id_level')->toArray();
        $guard = '';
        $level = '';
        if (Auth::guard('web')->check()) {
            $guard = 'web';
            $level = 'user';
        } elseif (Auth::guard('pegawai')->check()) {
            $guard = 'pegawai';
            $level = 'pegawai';
        } elseif (Auth::guard('anggota')->check()) {
            $guard = 'anggota';
            $level = 'anggota';
        }
        $sidebar = $this->getMenuSidebar($guard, $level);
        $allLevels = db_user_level::whereNotIn('id_level', $levelIds)->whereNot('id_level', 1)->get();
        $userLevels = db_user_level_akses::where("id_pegawai", $id)->whereIn('id_level', $levelIds)->get();
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];
        $pegawaiId = $id;
        return view('pegawai.role', compact('menus', 'menuIds', 'userLevels', 'allLevels', 'pegawaiId'));
    }
    public function rolePegawaiStore(Request $request, $id)
    {
        $guard = '';
        $level = '';
        if (Auth::guard('web')->check()) {
            $guard = 'web';
            $level = 'user';
        } elseif (Auth::guard('pegawai')->check()) {
            $guard = 'pegawai';
            $level = 'pegawai';
        } elseif (Auth::guard('anggota')->check()) {
            $guard = 'anggota';
            $level = 'anggota';
        }
        $sidebar = $this->getMenuSidebar($guard, $level);
        $levelAkses = db_user_level_akses::where("id_pegawai", $id)->get();

        db_user_level_akses::create([
            'id_user' => 0,
            'jenis_user' => $request->id_level,
            'id_level' => $request->id_level,
            'status' => 0,
            'id_pegawai' => $id,
            'id_anggota' => 0
        ]);
        $levelIds = $levelAkses->pluck('id_level')->toArray();
        $allLevels = db_user_level::whereNotIn('id_level', $levelIds)->get();
        $userLevels = db_user_level_akses::where("id_pegawai", $id)->whereIn('id_level', $levelIds)->get();
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        return redirect()->route('rolePegawai', compact('menus', 'menuIds', 'userLevels', 'allLevels', 'id'));
    }
    public function rolePegawaiDelete(Request $request, $id)
    {
        $guard = '';
        $level = '';
        if (Auth::guard('web')->check()) {
            $guard = 'web';
            $level = 'user';
        } elseif (Auth::guard('pegawai')->check()) {
            $guard = 'pegawai';
            $level = 'pegawai';
        } elseif (Auth::guard('anggota')->check()) {
            $guard = 'anggota';
            $level = 'anggota';
        }
        $sidebar = $this->getMenuSidebar($guard, $level);
        $levelId = $request->id_level;
        $levelAkses = db_user_level_akses::where("id_pegawai", $id)->get();
        $levelIds = $levelAkses->pluck('id_level')->toArray();
        $allLevels = db_user_level::whereNotIn('id_level', $levelIds)->get();
        $userLevels = db_user_level_akses::where("id_pegawai", $id)->whereIn('id_level', $levelIds)->get();
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $dataRole = db_user_level_akses::where('id_level', $levelId)->where('id_pegawai', $id)->first();
        $dataRole->delete();

        return redirect()->route('rolePegawai', compact('menus', 'menuIds', 'userLevels', 'allLevels', 'id'));
    }

    public function inputPegawaiDelete($id)
    {
        $pegawai = db_pegawai::find($id);
        $levelAkses = db_user_level_akses::where('id_pegawai', $id)->first();
        $levelAkses->delete();
        $pegawai->delete();

        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $dataPegawai = db_pegawai::all();
        return redirect()->route('inputPegawai', compact('menus', 'menuIds', 'dataPegawai'));
    }
    public function inputAnggota()
    {
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];
        $levelAkses = $sidebar['levelAkses'];
        $dataAkses = db_user_akses::where('id_level', $levelAkses->id_level)->where('id_menu', 7)->get();

        $dataAnggota = db_anggota::all();
        return view('anggota.index', compact('menus', 'menuIds', 'dataAnggota', 'dataAkses'));
    }

    public function inputAnggotaCreate()
    {
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        return view('anggota.add', compact('menus', 'menuIds'));
    }

    public function inputAnggotaStore(Request $request)
    {
        $request->validate([
            'nama_anggota' => 'required',
            'email_anggota' => 'required|email',
            'password_anggota' => 'required|min:8',
            'status' => 'required|boolean',
            'hp_anggota' => 'required|numeric',
        ]);
        $anggota = db_anggota::create([
            'nama_anggota' => $request->nama_anggota,
            'email_anggota' => $request->email_anggota,
            'hp_anggota' => $request->hp_anggota,
            'password_anggota' => bcrypt($request->password_anggota),
            'status' => $request->status,
        ]);
        $id_anggota = $anggota->id_anggota;
        $levelAkses = db_user_level_akses::create([
            'id_anggota' => $id_anggota,
            'jenis_user' => 4,
            'id_level' => 4,
            'status' => $request->status,
            'id_user' => 0,
            'id_pegawai' => 0
        ]);

        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];
        $dataAnggota = db_anggota::all();

        return redirect()->route('inputAnggota', compact('menus', 'menuIds', 'dataAnggota'));
    }

    public function inputAnggotaEdit($id)
    {
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $anggota = db_anggota::find($id);

        return view('anggota.edit', compact('menus', 'menuIds', 'anggota'));
    }

    public function inputAnggotaUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_anggota' => 'required',
            'email_anggota' => 'required|email',
            'password_anggota' => 'required|min:8',
            'status' => 'required|boolean',
            'hp_anggota' => 'required|numeric',
        ]);

        $anggota = db_anggota::find($id);
        $anggota->update([
            'nama_anggota' => $request->nama_anggota,
            'email_anggota' => $request->email_anggota,
            'hp_anggota' => $request->hp_anggota,
            'password_anggota' => bcrypt($request->password_anggota),
            'status' => $request->status,
        ]);
        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $dataAnggota = db_pegawai::all();

        return redirect()->route('inputAnggota', compact('menus', 'menuIds', 'dataAnggota'));
    }

    public function inputAnggotaDelete($id)
    {
        $anggota = db_anggota::find($id);
        $levelAkses = db_user_level_akses::where('id_anggota', $id)->first();
        $levelAkses->delete();
        $anggota->delete();

        $sidebar = [];
        if (Auth::guard('web')->check()) {
            $sidebar = $this->getMenuSidebar('web', 'user');
        } elseif (Auth::guard('pegawai')->check()) {
            $sidebar = $this->getMenuSidebar('pegawai', 'pegawai');
        } elseif (Auth::guard('anggota')->check()) {
            $sidebar = $this->getMenuSidebar('anggota', 'anggota');
        }
        $menus = $sidebar['menus'];
        $menuIds = $sidebar['menuIds'];

        $dataAnggota = db_anggota::all();
        return redirect()->route('inputAnggota', compact('menus', 'menuIds', 'dataAnggota'));
    }
}
