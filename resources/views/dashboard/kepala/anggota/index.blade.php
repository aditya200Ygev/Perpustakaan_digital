@extends('dashboard.kepala.layouts.app')

@section('title', 'Data SDM — Kepala Perpustakaan')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER SECTION --}}
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Manajemen SDM Perpustakaan</h1>
                <p class="text-sm text-gray-500 font-medium">Monitoring data seluruh petugas operasional dan anggota aktif.</p>
            </div>

            <div class="flex gap-3">
                <div class="bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total Petugas</p>
                    <p class="text-lg font-black text-indigo-600">{{ $petugas->count() }}</p>
                </div>
                <div class="bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total Anggota</p>
                    <p class="text-lg font-black text-blue-600">{{ $anggota->count() }}</p>
                </div>
            </div>
        </div>

        {{-- SECTION 1: DATA PETUGAS (OPERATOR) --}}
        <div class="mb-10">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-2 h-6 bg-indigo-500 rounded-full"></div>
                <h2 class="text-lg font-bold text-gray-700">Petugas Perpustakaan</h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-indigo-50/50 text-indigo-400 text-[10px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 w-12 text-center">No</th>
                            <th class="px-6 py-4">Nama Petugas</th>
                            <th class="px-6 py-4">Email / Akun</th>
                            <th class="px-6 py-4">NIP / ID Kerja</th>
                            <th class="px-6 py-4 text-right">Status Akses</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @foreach($petugas as $p)
                        <tr class="hover:bg-indigo-50/20 transition-all">
                            <td class="px-6 py-4 text-center text-gray-400 font-medium">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                                        {{ strtoupper(substr($p->name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-gray-700">{{ $p->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $p->email }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $p->nip ?? 'OPS-'.(100 + $p->id) }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-md border border-green-100 uppercase">Aktif</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SECTION 2: DATA ANGGOTA (SISWA) --}}
        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="w-2 h-6 bg-blue-500 rounded-full"></div>
                <h2 class="text-lg font-bold text-gray-700">Daftar Anggota (Siswa)</h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 text-center w-12">No</th>
                                <th class="px-6 py-4">Nama Siswa</th>
                                <th class="px-6 py-4">ID / NIS</th>
                                <th class="px-6 py-4">Kelas & Jurusan</th>
                                <th class="px-6 py-4">Tgl Gabung</th>
                                <th class="px-6 py-4 text-right">Riwayat</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($anggota as $index => $item)
                            <tr class="hover:bg-gray-50/80 transition-all group">
                                <td class="px-6 py-4 text-center text-gray-400 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs overflow-hidden">
                                            @if(optional($item->user)->photo)
                                                <img src="{{ asset('storage/'.$item->user->photo) }}" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($item->user->name ?? 'A', 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-800 leading-tight">{{ $item->user->name ?? 'N/A' }}</span>
                                            <span class="text-[9px] text-gray-400">{{ $item->user->email ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-gray-600 font-bold">{{ $item->nis }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-bold text-blue-600 uppercase">{{ $item->kelas }} {{ $item->jurusan }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">{{ $item->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-gray-400 hover:text-blue-600 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-400 italic">Belum ada anggota terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
