@extends('dashboard.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="bg-gray-50 min-h-screen p-4 md:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">
                    Data <span class="text-blue-600">Peminjaman</span>
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Kelola pengajuan, peminjaman aktif, dan pengembalian buku.
                </p>
            </div>
        </div>

        {{-- FILTER LAPORAN --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <form method="GET" class="flex flex-wrap items-end gap-4">

                {{-- 🔍 SEARCH --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                        Cari Data
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama siswa atau judul buku..."
                           class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none border transition-all text-sm font-bold">
                </div>

                {{-- 📅 FILTER TANGGAL --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                        Dari Tanggal
                    </label>
                    <input type="date" name="from" value="{{ request('from') }}"
                           class="border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none border text-sm bg-white font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                        Sampai Tanggal
                    </label>
                    <input type="date" name="to" value="{{ request('to') }}"
                           class="border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none border text-sm bg-white font-bold">
                </div>

                {{-- 📊 FILTER STATUS --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                        Status
                    </label>
                    <select name="status" class="border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none border text-sm bg-white font-bold">
                        <option value="">Semua Status</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>
                            🔔 Diajukan
                        </option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>
                            📖 Dipinjam
                        </option>
                        <option value="pengembalian_diajukan" {{ request('status') == 'pengembalian_diajukan' ? 'selected' : '' }}>
                            📩 Menunggu ACC Pengembalian
                        </option>
                        @if($peminjaman->contains('status', 'dikembalikan'))
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>
                            📦 Dikembalikan (Legacy)
                        </option>
                        @endif
                        <option value="denda" {{ request('status') == 'denda' ? 'selected' : '' }}>
                            ⚠️ Denda
                        </option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                            ✅ Selesai
                        </option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                            ❌ Ditolak
                        </option>
                    </select>
                </div>

                {{-- 🔘 BUTTONS --}}
                <div class="flex gap-2">
                    <button type="submit" class="bg-gray-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-gray-200">
                        Filter
                    </button>
                    <a href="{{ route('petugas.peminjaman.index') }}"
                       class="bg-gray-100 text-gray-500 px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gray-200 transition-all text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- TABLE DATA --}}
        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                Peminjam
                            </th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                Buku
                            </th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                Durasi
                            </th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">
                                Status
                            </th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($peminjaman as $item)
                        @php
                            // 🔥 HITUNG INFO DENDA (untuk display)
                            $tglKembali = \Carbon\Carbon::parse($item->tgl_kembali);
                            $tglAkhir = in_array($item->status, ['selesai', 'dikembalikan', 'pengembalian_diajukan'])
                                ? \Carbon\Carbon::parse($item->updated_at)
                                : \Carbon\Carbon::now();
                            $hariTelat = max(0, $tglAkhir->diffInDays($tglKembali));
                            $totalDenda = $hariTelat * 5000;
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors">

                            {{-- 👤 FOTO & DATA USER --}}
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 border-2 border-white shadow-sm flex-shrink-0">
                                        @if($item->user && $item->user->photo)
                                            <img src="{{ asset('storage/' . $item->user->photo) }}"
                                                 class="w-full h-full object-cover"
                                                 alt="{{ $item->user->name }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-500 text-xs font-black">
                                                {{ substr($item->user->name ?? '?', 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 text-sm leading-tight">
                                            {{ optional($item->user)->name ?? '-' }}
                                        </span>
                                        <span class="text-[10px] text-blue-500 uppercase font-black tracking-tighter">
                                            Kelas: {{ $item->user->anggota->kelas ?? 'Umum' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- 📚 FOTO & DATA BUKU --}}
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-14 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 shadow-sm border border-gray-100">
                                        @if($item->buku && $item->buku->cover)
                                            <img src="{{ asset('storage/'.$item->buku->cover) }}"
                                                 class="w-full h-full object-cover"
                                                 alt="Cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[8px] font-black text-gray-300 uppercase p-1 text-center leading-none">
                                                No Cover
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800 text-sm line-clamp-1">
                                            {{ optional($item->buku)->judul ?? '-' }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">
                                            {{ $item->jumlah ?? 1 }} Buku
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- 📅 DURASI --}}
                            <td class="p-5">
                                <div class="flex flex-col text-xs font-bold gap-0.5">
                                    <span class="text-gray-600">
                                        📥 P: {{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}
                                    </span>
                                    <span class="text-red-500 italic">
                                        📤 K: {{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d/m/Y') }}
                                    </span>
                                    {{-- ✅ HARI AKHIR: Tampilkan jika sudah selesai/dikembalikan --}}
                                    @if(in_array($item->status, ['selesai', 'denda', 'dikembalikan', 'pengembalian_diajukan']))
                                        <span class="text-green-600">
                                            ✅ Selesai: {{ $tglAkhir->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- 🏷️ STATUS BADGE (FIXED: Tampilkan Info Denda pada Status Selesai) --}}
                            <td class="p-5 text-center">
                                @switch($item->status)
                                    @case('diajukan')
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-[9px] font-black uppercase rounded-full">
                                            🔔 Diajukan
                                        </span>
                                        @break

                                    @case('dipinjam')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[9px] font-black uppercase rounded-full">
                                            📖 Dipinjam
                                        </span>
                                        @break

                                    @case('pengembalian_diajukan')
                                        <span class="px-3 py-1 bg-indigo-600 text-white text-[9px] font-black uppercase rounded-full shadow-sm animate-pulse">
                                            📩 Menunggu ACC
                                        </span>
                                        @break

                                    @case('dikembalikan')
                                        <span class="px-3 py-1 bg-gray-200 text-gray-600 text-[9px] font-black uppercase rounded-full">
                                            📦 Dikembalikan (Legacy)
                                        </span>
                                        @break

                                    {{-- ✅ DENDA: Tampilkan info lunas + nominal SELALU muncul --}}
                                    @case('denda')
                                        <div class="inline-flex flex-col items-center gap-1">
                                            <span class="px-3 py-1 bg-red-500 text-white text-[9px] font-black uppercase rounded-full">
                                                ⚠️ Denda
                                            </span>
                                            {{-- ✅ NOMINAL DENDA SELALU MUNCUL (menggunakan $totalDenda = $hariTelat * 5000) --}}
                                            <span class="text-[8px] font-bold text-gray-700">
                                                Rp {{ number_format($totalDenda, 0, ',', '.') }}
                                            </span>
                                            {{-- Badge status pembayaran --}}
                                            @if($item->is_paid)
                                                <span class="text-[8px] font-black text-green-600 bg-green-50 px-2 py-0.5 rounded">
                                                    ✅ Lunas
                                                </span>
                                            @else
                                                <span class="text-[8px] font-black text-red-400">
                                                    ❌ Belum Bayar
                                                </span>
                                            @endif
                                        </div>
                                        @break

                                    {{-- ✅ SELESAI: SELALU tampilkan info denda jika pernah telat (FIXED) --}}
                                    @case('selesai')
                                        <div class="inline-flex flex-col items-center gap-1">
                                            <span class="px-3 py-1 bg-green-100 text-green-700 text-[9px] font-black uppercase rounded-full">
                                                ✅ Selesai
                                            </span>
                                            {{-- 📋 TAMPILKAN INFO DENDA JIKA PERNAH TELAT (baik lunas atau tidak) --}}
                                            @if($hariTelat > 0)
                                                <div class="flex flex-col items-center mt-0.5">
                                                    <span class="text-[8px] font-bold text-gray-600">
                                                        Rp {{ number_format($totalDenda, 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-[8px] font-black text-green-600 bg-green-50 px-2 py-0.5 rounded mt-0.5">
                                                        ✓ Lunas
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        @break

                                    @case('ditolak')
                                        <span class="px-3 py-1 bg-gray-100 text-gray-400 text-[9px] font-black uppercase rounded-full">
                                            ❌ Ditolak
                                        </span>
                                        @break

                                    @default
                                        <span class="px-3 py-1 bg-gray-100 text-gray-400 text-[9px] font-black uppercase rounded-full">
                                            -
                                        </span>
                                @endswitch
                            </td>

                            {{-- ⚙️ AKSI --}}
                            <td class="p-5">
                                <div class="flex justify-end gap-2">

                                    {{-- 🖨️ CETAK BUKTI --}}
                                    @if(in_array($item->status, ['dipinjam', 'denda', 'selesai', 'pengembalian_diajukan', 'dikembalikan']))
                                    <a href="{{ route('peminjaman.bukti', $item->id) }}"
                                       class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2 rounded-lg transition-all"
                                       title="Cetak Bukti">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                    @endif

                                    {{-- ✅ / ❌ APPROVE PENGAJUAN PINJAM --}}
                                    @if($item->status == 'diajukan')
                                        <form action="{{ route('pinjam.approve', $item->id) }}" method="POST">
                                            @csrf
                                            <button class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-all" title="Setujui">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('pinjam.tolak', $item->id) }}" method="POST">
                                            @csrf
                                            <button class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition-all" title="Tolak">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- ✅ TERIMA PENGEMBALIAN (Status Baru) --}}
                                    @if($item->status == 'pengembalian_diajukan')
                                        <form action="{{ route('pinjam.acc.kembali', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Terima pengembalian buku ini? Stok akan ditambahkan.')">
                                            @csrf
                                            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-sm">
                                                ✅ Terima
                                            </button>
                                        </form>
                                    @endif

                                    {{-- ⚠️ Legacy: Support data lama dengan status 'dikembalikan' --}}
                                    @if($item->status == 'dikembalikan')
                                        <form action="{{ route('pinjam.acc.kembali', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Terima pengembalian buku ini? (Data Legacy)')">
                                            @csrf
                                            <button class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                                                ✅ Terima (Legacy)
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="text-gray-200 text-6xl mb-4 font-black italic">EMPTY</div>
                                    <p class="text-gray-400 font-medium italic text-sm tracking-widest uppercase">
                                        Tidak ada data peminjaman ditemukan.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 📄 PAGINATION --}}
        @if(method_exists($peminjaman, 'links'))
        <div class="mt-6">
            {{ $peminjaman->appends(request()->query())->links() }}
        </div>
        @endif

    </div>
</div>
@endsection
