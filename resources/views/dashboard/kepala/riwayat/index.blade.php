@extends('dashboard.kepala.layouts.app')

@section('title', 'Riwayat Anggota — Kepala Perpustakaan')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- ================= HEADER ================= --}}
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">
                    RIWAYAT <span class="text-blue-600">ANGGOTA</span>
                </h1>
                <p class="text-sm text-gray-500 font-medium">
                    Monitoring seluruh aktivitas peminjaman anggota perpustakaan.
                </p>
            </div>
            <div class="flex gap-3">
                <div class="bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total Transaksi</p>
                    <p class="text-lg font-black text-blue-600">{{ $stats['total_transaksi'] }}</p>
                </div>
                <div class="bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Sedang Dipinjam</p>
                    <p class="text-lg font-black text-amber-600">{{ $stats['dipinjam'] }}</p>
                </div>
                <div class="bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm text-center">
                    <p class="text-[10px] font-bold text-red-400 uppercase">Denda Aktif</p>
                    <p class="text-lg font-black text-red-600">{{ $stats['denda_aktif'] }}</p>
                </div>
            </div>
        </div>

        {{-- ================= FILTER ADVANCED ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <form method="GET" class="space-y-4">

                {{-- Row 1: Search & Filter Anggota --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                            🔍 Cari Anggota / Buku
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nama, NIS, atau judul buku..."
                               class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none border text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                            👤 Filter per Anggota
                        </label>
                        <select name="anggota_id" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none border text-sm bg-white">
                            <option value=""> Semua Anggota</option>
                            @foreach($listAnggota as $a)
                                <option value="{{ $a->user_id }}" {{ request('anggota_id') == $a->user_id ? 'selected' : '' }}>
                                    {{ $a->user->name }} ({{ $a->nis }} - {{ $a->kelas }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                            📊 Status
                        </label>
                        <select name="status" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none border text-sm bg-white">
                            <option value=""> Semua Status</option>
                            <option value="dipinjam" {{ request('status')=='dipinjam'?'selected':'' }}>📖 Dipinjam</option>
                            <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>✅ Selesai</option>
                            <option value="denda" {{ request('status')=='denda'?'selected':'' }}>⚠️ Denda</option>
                            <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>❌ Ditolak</option>
                        </select>
                    </div>
                </div>

                {{-- Row 2: Tanggal & Checkbox --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                            📅 Dari Tanggal
                        </label>
                        <input type="date" name="from" value="{{ request('from') }}"
                               class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none border text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                            📅 Sampai Tanggal
                        </label>
                        <input type="date" name="to" value="{{ request('to') }}"
                               class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none border text-sm">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition-all">
                            🔍 Filter
                        </button>
                        <a href="{{ route('kepala.riwayat.anggota') }}"
                           class="px-4 py-2.5 rounded-xl text-sm font-bold bg-gray-100 hover:bg-gray-200 text-gray-600 transition-all">
                            ↺ Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- ================= TABLE RIWAYAT ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-center w-12">No</th>
                            <th class="px-6 py-4">Anggota</th>
                            <th class="px-6 py-4">Buku</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($riwayat as $index => $item)
                        @php
                            // Hitung denda untuk display
                            $hariTelat = 0; $estimasiDenda = 0;
                            if ($item->tgl_kembali) {
                                $deadline = \Carbon\Carbon::parse($item->tgl_kembali);
                                $tglAkhir = in_array($item->status, ['selesai', 'dikembalikan'])
                                    ? \Carbon\Carbon::parse($item->updated_at) : now();
                                $hariTelat = $tglAkhir->gt($deadline) ? $tglAkhir->diffInDays($deadline) : 0;
                                $estimasiDenda = $hariTelat * 5000;
                            }
                            $dendaReal = $item->keuangan?->total_denda ?? $estimasiDenda;
                            $sudahDibayar = $item->is_paid ?? false;
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-all">
                            {{-- No --}}
                            <td class="px-6 py-4 text-center text-gray-400 font-medium">
                                {{ $riwayat->firstItem() + $index }}
                            </td>

                            {{-- Anggota --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs overflow-hidden">
                                        @if($item->user?->photo)
                                            <img src="{{ asset('storage/'.$item->user->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($item->user->name ?? 'A', 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800 leading-tight">{{ $item->user->name ?? '-' }}</span>
                                        <span class="text-[9px] text-gray-400">
                                            {{ $item->user->anggota?->nis }} • {{ $item->user->anggota?->kelas }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Buku --}}
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800 text-sm truncate max-w-[200px]">
                                    {{ $item->buku->judul ?? '-' }}
                                </div>
                                <div class="text-[10px] text-gray-400">📚 {{ $item->jumlah ?? 1 }} Buku</div>
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-6 py-4 text-xs">
                                <div class="space-y-1">
                                    <div><span class="text-gray-400">P:</span> {{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</div>
                                    <div class="text-red-500"><span class="text-gray-400">K:</span> {{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d/m/Y') }}</div>
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4 text-center">
                                @if($item->status == 'denda')
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="px-2 py-1 text-[9px] font-bold rounded-full {{ $sudahDibayar ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $sudahDibayar ? '✅ Lunas' : '❌ Belum' }}
                                        </span>
                                        <span class="text-[9px] text-gray-500">⚠️ Denda</span>
                                    </div>
                                @elseif($item->status == 'selesai' && ($item->is_denda ?? false))
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="px-2 py-1 text-[9px] font-bold bg-green-100 text-green-700 rounded-full">✅ Selesai</span>
                                        <span class="text-[9px] text-gray-500">📋 Lunas</span>
                                    </div>
                                @elseif($item->status == 'selesai')
                                    <span class="px-2 py-1 text-[9px] font-bold bg-green-100 text-green-700 rounded-full">✅ Selesai</span>
                                @elseif($item->status == 'dipinjam')
                                    <span class="px-2 py-1 text-[9px] font-bold bg-amber-100 text-amber-700 rounded-full">📖 Dipinjam</span>
                                @else
                                    <span class="px-2 py-1 text-[9px] font-bold bg-gray-100 text-gray-600 rounded-full">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>

                            {{-- Denda --}}
                            <td class="px-6 py-4 text-right font-bold text-gray-800">
                                @if($sudahDibayar && $dendaReal > 0)
                                    <span class="text-green-600">Rp {{ number_format($dendaReal, 0, ',', '.') }}</span>
                                @elseif($item->status == 'denda' && !$sudahDibayar)
                                    <span class="text-red-600">Rp {{ number_format($estimasiDenda, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400 italic">
                                Tidak ada data riwayat ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($riwayat->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $riwayat->appends(request()->query())->links() }}
            </div>
            @endif
        </div>

        {{-- Export Button (Opsional) --}}


    </div>
</div>
@endsection
