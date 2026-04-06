@extends('dashboard.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-7xl mx-auto">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-bold">Data Peminjaman</h1>
        </div>
<!-- 🔍 SEARCH + FILTER -->
<form method="GET" class="mb-4 flex flex-wrap gap-3 items-end">

    <!-- SEARCH -->
    <div>
        <label class="text-sm">Cari</label>
        <input type="text" name="search"
               value="{{ request('search') }}"
               placeholder="Nama / Buku..."
               class="border p-2 rounded w-48">
    </div>

    <!-- TANGGAL DARI -->
    <div>
        <label class="text-sm">Dari</label>
        <input type="date" name="from"
               value="{{ request('from') }}"
               class="border p-2 rounded">
    </div>

    <!-- TANGGAL SAMPAI -->
    <div>
        <label class="text-sm">Sampai</label>
        <input type="date" name="to"
               value="{{ request('to') }}"
               class="border p-2 rounded">
    </div>

    <!-- BUTTON -->
    <div class="flex gap-2">
        <button class="bg-blue-500 text-white px-4 py-2 rounded">
            🔍 Cari
        </button>

        <a href="{{ route('petugas.peminjaman.index') }}"
           class="bg-gray-400 text-white px-4 py-2 rounded">
            Reset
        </a>
    </div>

</form>
        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <table class="w-full text-sm">

                <!-- HEAD -->
                <thead class="bg-gray-200 text-gray-700 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Nama Peminjam</th>
                        <th class="p-3 text-left">Buku</th>
                        <th class="p-3 text-left">Jumlah</th>
                        <th class="p-3 text-left">Tanggal Pinjam</th>
                        <th class="p-3 text-left">Tanggal Kembali</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody>

                    @forelse($peminjaman as $index => $item)
                    <tr class="border-b hover:bg-gray-50 transition">

                        <!-- NO -->
                        <td class="p-3">{{ $index + 1 }}</td>

                        <!-- NAMA -->
                        <td class="p-3">
                            {{ optional($item->user)->name ?? '-' }}
                        </td>

                        <!-- BUKU -->
                        <td class="p-3">
                            {{ optional($item->buku)->judul ?? '-' }}
                        </td>

                        <!-- JUMLAH -->
                        <td class="p-3">
                            {{ $item->jumlah }}
                        </td>

                        <!-- TGL PINJAM -->
                        <td class="p-3">
                            {{ $item->tgl_pinjam }}
                        </td>

                        <!-- TGL KEMBALI -->
                        <td class="p-3">
                            {{ $item->tgl_kembali }}
                        </td>

                     <td class="p-3">

    @if($item->status == 'diajukan')
        <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded text-xs">
            Diajukan
        </span>

    @elseif($item->status == 'dipinjam')
        <span class="px-2 py-1 bg-blue-200 text-blue-800 rounded text-xs">
            Dipinjam
        </span>

    @elseif($item->status == 'denda')
        <span class="px-2 py-1 bg-red-500 text-white rounded text-xs">
            Denda (Terlambat)
        </span>

    @elseif($item->status == 'dikembalikan')
        <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-xs">
            Dikembalikan
        </span>

    @elseif($item->status == 'ditolak')
        <span class="px-2 py-1 bg-gray-300 text-gray-800 rounded text-xs">
            Ditolak
        </span>

    @endif

</td>
                        <!-- AKSI -->
                        <td class="p-3 flex gap-2">

                            {{-- SETUJUI & TOLAK --}}
                            @if($item->status == 'diajukan')

                            <!-- SETUJUI -->
                            <form action="{{ route('pinjam.approve', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Setujui peminjaman ini?')">
                                @csrf
                                <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                                    Setujui
                                </button>
                            </form>

                            <!-- TOLAK -->
                            <form action="{{ route('pinjam.tolak', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menolak peminjaman ini?')">
                                @csrf
                                <button class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">
                                    Tolak
                                </button>
                            </form>

                            @endif

                            {{-- KEMBALIKAN --}}
                            @if($item->status == 'dipinjam')
                            <form action="{{ route('pinjam.kembali', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Konfirmasi pengembalian buku?')">
                                @csrf
                                <button class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600">
                                    Kembalikan
                                </button>
                            </form>
                            @endif

                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="8" class="text-center p-10 text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-4xl">📭</span>
                                <p>Data peminjaman belum tersedia</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection
