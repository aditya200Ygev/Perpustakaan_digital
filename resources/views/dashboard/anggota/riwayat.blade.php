@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')

@section('content')

@php use Illuminate\Support\Facades\Storage; @endphp

<div class="bg-gray-50 min-h-screen py-10 px-4">
    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 uppercase">
                    Riwayat <span class="text-blue-600">Peminjaman</span>
                </h1>
                <p class="text-gray-500 text-sm">Daftar semua aktivitas peminjaman buku Anda.</p>
            </div>

            <a href="/" class="text-sm font-bold text-gray-500 hover:text-gray-900">
                ← Kembali
            </a>
        </div>

        {{-- CARD --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-left text-xs uppercase text-gray-500">
                            <th class="p-4">No</th>
                            <th class="p-4">Buku</th>
                            <th class="p-4">Jumlah</th>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-right">Denda</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
    @forelse($riwayat as $i => $row)
    <tr class="hover:bg-gray-50 transition">

        {{-- NO --}}
        <td class="p-4 text-gray-500">{{ $i+1 }}</td>

        {{-- BUKU + FOTO --}}
        <td class="p-4">
            <div class="flex items-center gap-4">

                {{-- FOTO --}}
                <div class="w-14 h-20 bg-gray-100 rounded-lg overflow-hidden border flex-shrink-0">
                    @if($row->buku->cover)
                        <img src="{{ Storage::url($row->buku->cover) }}"
                             alt="{{ $row->buku->judul }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <span class="text-lg">📚</span>
                        </div>
                    @endif
                </div>

                {{-- INFO --}}
                <div class="min-w-0">
                    <div class="font-bold text-gray-800 truncate">
                        {{ $row->buku->judul ?? '-' }}
                    </div>
                    <div class="text-xs text-gray-400 truncate">
                        {{ $row->buku->penulis ?? '' }}
                    </div>
                    {{-- ✅ JUMLAH DIPINJAM (bukan stok) --}}
                    <div class="text-[10px] text-blue-500 font-bold">
                        Dipinjam: {{ $row->jumlah ?? 1 }} buku
                    </div>
                </div>

            </div>
        </td>

        {{-- JUMLAH DIPINJAM (Kolom Terpisah) --}}
        <td class="p-4 text-center font-bold text-gray-700">
            {{ $row->jumlah ?? 1 }}
        </td>

        {{-- TANGGAL --}}
        <td class="p-4 text-xs">
            <div>Pinjam: <b>{{ \Carbon\Carbon::parse($row->tgl_pinjam)->format('d/m/Y') }}</b></div>
            <div class="text-red-500">
                Kembali:
                <b>
                    {{ $row->tgl_kembali ? \Carbon\Carbon::parse($row->tgl_kembali)->format('d/m/Y') : '-' }}
                </b>
            </div>
        </td>

        {{-- STATUS --}}
        <td class="p-4 text-center">
            @if($row->status == 'dipinjam')
                <span class="px-3 py-1 text-xs font-bold bg-blue-100 text-blue-600 rounded-full">
                    Dipinjam
                </span>
            @elseif($row->status == 'selesai')
                <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-600 rounded-full">
                    Selesai
                </span>
            @elseif($row->status == 'denda')
                <span class="px-3 py-1 text-xs font-bold rounded-full
                    {{ $row->is_paid ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    {{ $row->is_paid ? 'Lunas' : 'Belum Bayar' }}
                </span>
            @endif
        </td>

        {{-- DENDA --}}
        <td class="p-4 text-right font-bold text-gray-800">
            Rp {{ number_format($row->denda ?? 0, 0, ',', '.') }}
        </td>

    </tr>
    @empty
    <tr>
        <td colspan="6" class="text-center p-10 text-gray-400 italic">
            Belum ada riwayat peminjaman.
        </td>
    </tr>
    @endforelse
</tbody>

                    {{-- TOTAL --}}
                    @if($riwayat->count())
                    <tfoot class="bg-gray-50 font-bold">
                        <tr>
                            <td colspan="5" class="p-4 text-right text-xs uppercase">
                                Total Denda
                            </td>
                            <td class="p-4 text-right text-blue-600">
                                Rp {{ number_format($riwayat->sum('denda'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

    </div>
</div>

@endsection
