<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
  public function anggota()
{
    $user = auth()->user();

    $dipinjam = \App\Models\Peminjaman::with('buku')
        ->where('user_id', $user->id)
        ->where('status', 'dipinjam')
        ->get();

    $riwayat = \App\Models\Peminjaman::with('buku')
        ->where('user_id', $user->id)
        ->latest()
        ->limit(5)
        ->get();

    $totalPinjam = \App\Models\Peminjaman::where('user_id', $user->id)->count();

    return view('dashboard.anggota.index', compact(
        'user',
        'dipinjam',
        'riwayat',
        'totalPinjam'
    ));
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
