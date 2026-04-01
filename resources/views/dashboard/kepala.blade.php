@extends('layouts.app1')
@section('title', 'Dashboard — Kepala Perpustakaan')

@section('content')
<div class="db-wrap">
  <aside class="sidebar">
    <div class="sb-brand">📚 <span>Perpustakaan</span></div>
    <nav class="sb-nav">
      <a class="sb-link sb-link--on" href="#">🏠 Dashboard</a>
      <a class="sb-link" href="#">👮 Kelola Petugas</a>
      <a class="sb-link" href="#">📚 Kelola Data Buku</a>
      <a class="sb-link" href="#">📊 Lihat Laporan</a>
      <a class="sb-link" href="#">📜 Histori Laporan</a>
    </nav>
    <div class="sb-footer">
      <div class="sb-user">
        <div class="sb-avatar" style="background:#C0392B">{{ strtoupper(substr($user->name,0,1)) }}</div>
        <div>
          <div class="sb-uname">{{ $user->name }}</div>
          <div class="sb-urole">Kepala Perpustakaan</div>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sb-logout">🚪 Keluar</button>
      </form>
    </div>
  </aside>

  <main class="db-main">
    <div class="db-header">
      <div>
        <h1>Dashboard Kepala Perpustakaan</h1>
        <p class="db-sub">Kepala Perpustakaan &middot; NIP: {{ $user->nip }}</p>
      </div>
      <div class="db-avatar" style="background:#C0392B">{{ strtoupper(substr($user->name,0,1)) }}</div>
    </div>

    @if(session('success'))
      <div class="flash flash--success mb-4">✓ {{ session('success') }}</div>
    @endif

    <div class="stat-row">
      <div class="stat-card">
        <div class="stat-ico" style="background:#EFF6FF">👮</div>
        <div><div class="stat-n">0</div><div class="stat-l">Total Petugas</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-ico" style="background:#F0FFF4">📚</div>
        <div><div class="stat-n">0</div><div class="stat-l">Total Koleksi Buku</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-ico" style="background:#FFF7ED">📊</div>
        <div><div class="stat-n">0</div><div class="stat-l">Laporan Bulan Ini</div></div>
      </div>
    </div>

    <div class="info-card">
      <h3>Informasi Akun</h3>
      <table class="info-tbl">
        <tr><td>Nama Lengkap</td><td>{{ $user->name }}</td></tr>
        <tr><td>NIP</td><td>{{ $user->nip ?? '-' }}</td></tr>
        <tr><td>Email</td><td>{{ $user->email }}</td></tr>
        <tr><td>No. Telepon</td><td>{{ $user->no_telp ?? '-' }}</td></tr>
        <tr><td>Alamat</td><td>{{ $user->alamat ?? '-' }}</td></tr>
      </table>
    </div>
  </main>
</div>
@endsection
