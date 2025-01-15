<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\db_pegawai;
use Illuminate\Http\Request;
use App\Models\db_user_level_akses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function authenticateUser($user, $password, $levelKey, $guard)
    {
        if (!Hash::check($password,  $user->{"password_$levelKey"})) {
            return response()->json([
                'status_code' => 401,
                'message' => 'Password salah.',
                'data' => null
            ], 401);
        }

        $levelAkses = db_user_level_akses::with('level.userAkses.menu')
            ->where("id_{$levelKey}", $user->{"id_{$levelKey}"})
            ->first();
        $menus = $levelAkses->level->userAkses->pluck('menu');
        if (!$levelAkses) {
            return response()->json([
                'status_code' => 404,
                'message' => 'Level akses tidak ditemukan.',
                'data' => null
            ], 401);
        }

        if ($levelAkses->status != 1) {
            return response()->json([
                'status_code' => 401,
                'message' => 'Level akses tidak valid.',
                'data' => null
            ], 401);
        }

        Auth::guard($guard)->login($user);
        return response()->json([
            'status_code' => 200,
            'message' => 'Success.',
            'data' => [
                $user,
                $menus
            ]
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

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

        return response()->json([
            'status_code' => 404,
            'message' => 'Akun tidak ditemukan.',
            'data' => null
        ], 404);
    }
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
