@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')

@section('content')
@php use Illuminate\Support\Facades\Storage; @endphp

<div class="bg-gray-50 min-h-screen py-10 px-4">
    <div class="max-w-6xl mx-auto">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 uppercase">
                    Riwayat <span class="text-blue-600">Peminjaman</span>
                </h1>
                <p class="text-gray-500 text-sm">Daftar semua aktivitas peminjaman buku Anda.</p>
            </div>
            <a href="{{ route('dashboard.anggota') }}"
               class="text-sm font-bold text-gray-500 hover:text-gray-900 transition-colors">
                ← Kembali ke Dashboard
            </a>
        </div>

        {{-- ================= FILTER SECTION ================= --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <form method="GET" class="flex flex-wrap items-end gap-3">

                {{-- Dropdown Filter Status --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">
                        Filter Status
                    </label>
                    <select name="filter_status" onchange="this.form.submit()"
                        class="w-full border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none border bg-white">

                        <option value="">✨ Semua Transaksi</option>

                        <optgroup label="📖 Aktif">
                            <option value="dipinjam" {{ request('filter_status')=='dipinjam'?'selected':'' }}>
                                📖 Sedang Dipinjam
                            </option>
                        </optgroup>

                        <optgroup label="✅ Selesai">
                            <option value="selesai" {{ request('filter_status')=='selesai'?'selected':'' }}>
                                ✅ Selesai
                            </option>
                        </optgroup>

                        <optgroup label="⚠️ Denda">
                            <option value="denda" {{ request('filter_status')=='denda'?'selected':'' }}>
                                ⚠️ Semua Denda
                            </option>
                            <option value="denda_belum" {{ request('filter_status')=='denda_belum'?'selected':'' }}>
                                └─ ❌ Belum Bayar
                            </option>
                            <option value="denda_lunas" {{ request('filter_status')=='denda_lunas'?'selected':'' }}>
                                └─ ✅ Sudah Lunas
                            </option>
                        </optgroup>
                    </select>
                </div>

                {{-- Tombol Action --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold transition-all">
                        🔍 Filter
                    </button>
                    <a href="{{ route('anggota.riwayat') }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm font-bold transition-all">
                        ↺ Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- ================= TABEL RIWAYAT ================= --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    {{-- Header Tabel --}}
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-left text-xs uppercase text-gray-500">
                            <th class="p-4">No</th>
                            <th class="p-4">Buku</th>
                            <th class="p-4 text-center">Jumlah</th>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-right">Denda</th>
                        </tr>
                    </thead>

                    {{-- Body Tabel --}}
                    <tbody class="divide-y divide-gray-100">
                        @forelse($riwayat as $i => $row)
                        @php
                            // Hitung hari telat & estimasi denda
                            $hariTelat = 0;
                            $estimasiDenda = 0;

                            if ($row->tgl_kembali) {
                                $deadline = \Carbon\Carbon::parse($row->tgl_kembali);
                                $tglAkhir = in_array($row->status, ['selesai', 'dikembalikan', 'pengembalian_diajukan'])
                                    ? \Carbon\Carbon::parse($row->updated_at)
                                    : now();
                                $hariTelat = $tglAkhir->gt($deadline) ? $tglAkhir->diffInDays($deadline) : 0;
                                $estimasiDenda = $hariTelat * 5000;
                            }

                            // Ambil nominal denda
                            $dendaReal = $row->keuangan?->total_denda ?? $estimasiDenda;
                            $sudahDibayar = $row->is_paid ?? false;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- No --}}
                            <td class="p-4 text-gray-500 font-medium">{{ $i + 1 }}</td>

                            {{-- Buku + Cover --}}
                            <td class="p-4">
                                <div class="flex items-center gap-4">
                                    {{-- Cover Buku --}}
                                    <div class="w-14 h-20 bg-gray-100 rounded-lg overflow-hidden border flex-shrink-0">
                                        @if($row->buku->cover)
                                            <img src="{{ Storage::url($row->buku->cover) }}"
                                                 alt="{{ $row->buku->judul }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <span class="text-2xl">📚</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Info Buku --}}
                                    <div class="min-w-0">
                                        <div class="font-bold text-gray-800 truncate" title="{{ $row->buku->judul }}">
                                            {{ $row->buku->judul ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-400 truncate">
                                            {{ $row->buku->penulis ?? '' }}
                                        </div>
                                        <div class="text-[10px] text-blue-500 font-bold mt-1">
                                            Dipinjam: {{ $row->jumlah ?? 1 }} buku
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Jumlah --}}
                            <td class="p-4 text-center font-bold text-gray-700">
                                {{ $row->jumlah ?? 1 }}
                            </td>

                            {{-- Tanggal --}}
                            <td class="p-4 text-xs">
                                <div class="space-y-1">
                                    <div>
                                        <span class="text-gray-400">P:</span>
                                        <b>{{ \Carbon\Carbon::parse($row->tgl_pinjam)->format('d/m/Y') }}</b>
                                    </div>
                                    <div class="text-red-500">
                                        <span class="text-gray-400">K:</span>
                                        <b>{{ $row->tgl_kembali ? \Carbon\Carbon::parse($row->tgl_kembali)->format('d/m/Y') : '-' }}</b>
                                    </div>
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="p-4 text-center">
                                @if($row->status == 'dipinjam')
                                    <span class="px-3 py-1 text-xs font-bold bg-blue-100 text-blue-600 rounded-full">
                                        📖 Dipinjam
                                    </span>

                                @elseif($row->status == 'selesai' && ($row->is_denda ?? false))
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-600 rounded-full">
                                            ✅ Selesai
                                        </span>
                                        <span class="px-2 py-0.5 text-[9px] font-bold bg-gray-100 text-gray-600 rounded">
                                            📋 Pernah Denda
                                        </span>
                                    </div>

                                @elseif($row->status == 'selesai')
                                    <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-600 rounded-full">
                                        ✅ Selesai
                                    </span>

                                @elseif($row->status == 'denda')
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $sudahDibayar ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                            ⚠️ {{ $sudahDibayar ? 'Lunas' : 'Belum Bayar' }}
                                        </span>
                                    </div>

                                @else
                                    <span class="px-3 py-1 text-xs font-bold bg-gray-100 text-gray-600 rounded-full">
                                        {{ ucfirst($row->status) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Denda --}}
                            <td class="p-4 text-right font-bold text-gray-800">
                                @if($sudahDibayar && $dendaReal > 0)
                                    {{-- Sudah Dibayar --}}
                                    <div class="text-green-600">
                                        <div>Rp {{ number_format($dendaReal, 0, ',', '.') }}</div>
                                        <div class="text-[10px] font-normal text-green-500">✓ Lunas</div>
                                    </div>

                                @elseif($row->status == 'denda' && !$sudahDibayar)
                                    {{-- Belum Dibayar --}}
                                    <div class="text-red-600">
                                        <div>Rp {{ number_format($estimasiDenda, 0, ',', '.') }}</div>
                                        <div class="text-[10px] font-normal text-red-400">✗ Belum</div>
                                    </div>

                                @elseif($row->status == 'selesai' && ($row->is_denda ?? false))
                                    {{-- Historis Denda --}}
                                    <span class="text-gray-500">
                                        Rp {{ number_format($dendaReal, 0, ',', '.') }}
                                    </span>

                                @else
                                    {{-- Tidak Ada Denda --}}
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center p-12">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="text-4xl">📭</div>
                                    @if(request('filter_status'))
                                        <p class="text-gray-500 font-medium">Tidak ada data untuk filter ini.</p>
                                        <a href="{{ route('anggota.riwayat') }}"
                                           class="text-blue-600 hover:underline text-sm font-bold">
                                            Lihat semua riwayat →
                                        </a>
                                    @else
                                        <p class="text-gray-400 italic">Belum ada riwayat peminjaman.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    {{-- Footer: Total Denda Dibayar --}}
                    @if($riwayat->count())
                    <tfoot class="bg-gray-50 font-bold border-t">
                        <tr>
                            <td colspan="5" class="p-4 text-right text-xs uppercase text-gray-500">
                                💰 Total Denda Dibayar:
                            </td>
                            <td class="p-4 text-right text-blue-600">
                                @php
                                    $totalDibayar = $riwayat->filter(fn($r) => $r->is_paid && $r->keuangan?->total_denda)
                                        ->sum(fn($r) => $r->keuangan?->total_denda ?? 0);
                                @endphp
                                Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- Info Pagination (jika pakai paginate) --}}
        @if(method_exists($riwayat, 'links'))
            <div class="mt-4">
                {{ $riwayat->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
