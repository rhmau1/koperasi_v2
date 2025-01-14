<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function dashboardAdmin()
    {
        return view('dashboard.admin.index');
    }
    public function dashboardPegawai()
    {
        dd(Auth::guard('pegawai')->check());
        return view('dashboard.pegawai.index');
    }
}
