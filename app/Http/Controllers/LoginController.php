<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\db_pegawai;
use App\Models\db_anggota;
use App\Models\db_user_level_akses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    private function authenticateUser($user, $password, $levelKey, $guard)
    {
        if (!Hash::check($password,  $user->{"password_$levelKey"})) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ])->withInput();
        }

        $levelAkses = db_user_level_akses::where("id_{$levelKey}", $user->{"id_{$levelKey}"})->first();
        if (!$levelAkses) {
            return back()->withErrors([
                'email' => 'Level akses tidak ditemukan.',
            ])->withInput();
        }

        // if ($levelAkses->status != 1) {
        //     return back()->withErrors([
        //         'email' => 'Level akses tidak valid.',
        //     ])->withInput();
        // }

        Auth::guard($guard)->login($user);
        return redirect()->route('pilihRole');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the user is an admin
        $userAdmin = User::where('email_user', $request->email)->first();
        if ($userAdmin) {
            return $this->authenticateUser($userAdmin, $request->password, 'user', 'web');
        }

        // Check if the user is a pegawai
        $userPegawai = db_pegawai::where('email_pegawai', $request->email)->first();
        if ($userPegawai) {
            return $this->authenticateUser($userPegawai, $request->password, 'pegawai', 'pegawai');
        }
        $userAnggota = db_anggota::where('email_anggota', $request->email)->first();
        if ($userAnggota) {
            return $this->authenticateUser($userAnggota, $request->password, 'anggota', 'anggota');
        }

        // If no user is found
        return back()->withErrors([
            'email' => 'Email tidak ditemukan.',
        ])->withInput();
    }

    public function logoutAdmin(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah keluar sebagai admin.');
    }

    public function logoutPegawai(Request $request)
    {
        Auth::guard('pegawai')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah keluar sebagai pegawai.');
    }
    public function logoutAnggota(Request $request)
    {
        Auth::guard('anggota')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah keluar sebagai anggota.');
    }

    public function pilihRole()
    {
        $userId = '';
        $userLevels = [];
        if (Auth::guard('web')->check()) {
            $userId = Auth::guard('web')->id();
            $userLevels = db_user_level_akses::where('id_user', $userId)->get();
        } elseif (Auth::guard('pegawai')->check()) {
            $userId = Auth::guard('pegawai')->id();
            $userLevels = db_user_level_akses::where('id_pegawai', $userId)->get();
        } elseif (Auth::guard('anggota')->check()) {
            $userId = Auth::guard('anggota')->id();
            $userLevels = db_user_level_akses::where('id_anggota', $userId)->get();
        }
        return view('auth.pilih-role', compact('userLevels'));
    }

    public function pilihRoleUpdate($id)
    {
        $userId = '';
        $userLevels = [];
        $level = db_user_level_akses::where('id_level', $id)->first();
        if (Auth::guard('web')->check()) {
            $userId = Auth::guard('web')->id();
            $userLevels = db_user_level_akses::where('id_user', $userId)
                ->where('status', 1)
                ->first();
        } elseif (Auth::guard('pegawai')->check()) {
            $userId = Auth::guard('pegawai')->id();
            $userLevels = db_user_level_akses::where('id_pegawai', $userId)
                ->where('status', 1)
                ->first();
        } elseif (Auth::guard('anggota')->check()) {
            $userId = Auth::guard('anggota')->id();
            $userLevels = db_user_level_akses::where('id_anggota', $userId)
                ->where('status', 1)
                ->first();
        }
        if ($id != $userLevels->id_level) {
            $userLevels->update([
                'status' => 0
            ]);
            if (Auth::guard('web')->check()) {
                $level->update([
                    'id_user' => $userId,
                    'id_level' => $id,
                    'jenis_user' => $id,
                    'id_pegawai' => 0,
                    'id_anggota' => 0,
                    'status' => 1
                ]);
            } elseif (Auth::guard('pegawai')->check()) {
                $level->update([
                    'id_user' => 0,
                    'id_level' => $id,
                    'jenis_user' => $id,
                    'id_pegawai' => $userId,
                    'id_anggota' => 0,
                    'status' => 1
                ]);
            } elseif (Auth::guard('anggota')->check()) {
                $level->update([
                    'id_user' => 0,
                    'id_level' => $id,
                    'jenis_user' => $id,
                    'id_anggota' => $userId,
                    'id_pegawai' => 0,
                    'status' => 1
                ]);
            }
        }
        return redirect()->route('dashboard');
    }
}
