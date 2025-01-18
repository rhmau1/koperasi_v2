<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\db_pegawai;
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

        if ($levelAkses->status != 1) {
            return back()->withErrors([
                'email' => 'Level akses tidak valid.',
            ])->withInput();
        }

        Auth::guard($guard)->login($user);
        return redirect()->route('dashboard');
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
}
