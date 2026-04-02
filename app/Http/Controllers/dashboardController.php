<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function anggota()
    {
        // ambil user + relasi anggota
        $user = Auth::user()->load('anggota');

        return view('dashboard.anggota.index', compact('user'));
    }

    public function petugas()
    {
        $user = Auth::user();
        return view('dashboard.petugas.index', compact('user'));
    }

    public function kepala()
    {
        $user = Auth::user();
        return view('dashboard.kepala.index', compact('user'));
    }
}
