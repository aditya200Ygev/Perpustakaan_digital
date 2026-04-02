@extends('dashboard.app')

@section('title', 'Dashboard Petugas')

@section('content')

<!-- HEADER -->
<div class="flex justify-between items-center mb-6">
    <h1 class="text-xl font-bold">Dashboard</h1>
    <div class="text-sm text-gray-600">{{ Auth::user()->nip ?? '-' }}</div>
</div>

<!-- TOP CARDS -->
<div class="grid md:grid-cols-3 gap-6 mb-6">

    <div class="bg-blue-600 text-white p-5 rounded-xl shadow">
        <p class="text-sm">Ajuan peminjaman</p>
        <div class="text-3xl font-bold mt-2">10</div>
    </div>

    <div class="bg-cyan-600 text-white p-5 rounded-xl shadow">
        <p class="text-sm">Total Buku</p>
        <div class="text-3xl font-bold mt-2">10</div>
    </div>

    <div class="bg-yellow-500 text-white p-5 rounded-xl shadow">
        <p class="text-sm">Pinjaman aktif</p>
        <div class="text-3xl font-bold mt-2">10</div>
    </div>

</div>

<!-- SECOND -->
<div class="grid md:grid-cols-2 gap-6 mb-6">

    <div class="bg-gray-100 p-6 rounded-xl shadow text-center">
        <p class="font-semibold">TOTAL ANGGOTA</p>
        <div class="text-3xl font-bold mt-2">10</div>
    </div>

    <div class="bg-gray-100 p-6 rounded-xl shadow text-center">
        <p class="font-semibold">Ajuan Pengembalian</p>
        <div class="text-3xl font-bold mt-2">10</div>
    </div>

</div>

<!-- TABLE -->
<div class="bg-white rounded-xl shadow p-4">

    <h3 class="font-semibold mb-4">AJUAN PINJAMAN TERBARU</h3>

    <table class="w-full text-sm">
        <thead class="bg-blue-100">
            <tr>
                <th class="p-2 text-left">Nama</th>
                <th class="p-2 text-left">Buku</th>
                <th class="p-2 text-left">Tgl Ajuan</th>
                <th class="p-2 text-left">Status</th>
            </tr>
        </thead>

        <tbody>
            @for ($i = 0; $i < 3; $i++)
            <tr class="border-b">
                <td class="p-2 flex items-center gap-3">
                    <img src="https://via.placeholder.com/40" class="rounded-full">
                    Aditya Romansyah
                </td>
                <td class="p-2">Meminjam Buku Matematika</td>
                <td class="p-2">25 FEB 2026</td>
                <td class="p-2">
                    <span class="bg-green-500 text-white px-3 py-1 rounded text-xs">
                        SELESAI
                    </span>
                </td>
            </tr>
            @endfor
        </tbody>
    </table>

</div>

@endsection
