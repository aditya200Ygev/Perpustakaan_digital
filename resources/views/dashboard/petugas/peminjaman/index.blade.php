@extends('dashboard.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="bg-gray-50 min-h-screen p-4 md:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- ================= HEADER ================= --}}
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

        {{-- ================= FILTER LAPORAN ================= --}}
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
                        <option value="">✨ Semua Status</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>🔔 Diajukan</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>📖 Dipinjam</option>
                        <option value="pengembalian_diajukan" {{ request('status') == 'pengembalian_diajukan' ? 'selected' : '' }}>📩 Menunggu ACC</option>
                        <option value="denda" {{ request('status') == 'denda' ? 'selected' : '' }}>⚠️ Denda</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                    </select>
                </div>

                {{-- 🔘 BUTTONS --}}
                <div class="flex gap-2">
                    <button type="submit" class="bg-gray-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-gray-200">
                        🔍 Filter
                    </button>
                    <a href="{{ route('petugas.peminjaman.index') }}"
                       class="bg-gray-100 text-gray-500 px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gray-200 transition-all text-center">
                        ↺ Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- ================= TABLE DATA ================= --}}
        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Peminjam</th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Buku</th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Durasi</th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Status</th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($peminjaman as $item)
                        @php
                            // 🔥 HITUNG INFO DENDA & TANGGAL SELESAI
                            $tglKembali = \Carbon\Carbon::parse($item->tgl_kembali);
                            $tglAkhir = null;
                            $hariTelat = 0;
                            $estimasiDenda = 0;
                            $dendaReal = 0;
                            $sudahDibayar = $item->is_paid ?? false;

                            // 🔥 TENTUKAN $tglAkhir BERDASARKAN STATUS
                            if (in_array($item->status, ['selesai', 'dikembalikan', 'pengembalian_diajukan'])) {
                                $tglAkhir = \Carbon\Carbon::parse($item->updated_at);
                            } elseif ($item->status == 'denda') {
                                $tglAkhir = $tglKembali;
                            }

                            // 🔥 HITUNG HARI TELAT & ESTIMASI DENDA
                            if ($tglAkhir && $tglAkhir->gt($tglKembali)) {
                                $hariTelat = $tglAkhir->diffInDays($tglKembali);
                                $estimasiDenda = $hariTelat * 5000;
                            }

                            // 🔥 AMBIL DENDA REAL DARI TABEL KEUANGAN
                            if ($item->keuangan && $item->keuangan->total_denda > 0) {
                                $dendaReal = $item->keuangan->total_denda;
                                $sudahDibayar = true;
                            }

                            // ✅ FINAL: Gunakan denda real jika ada, else estimasi
                            $totalDenda = $dendaReal > 0 ? $dendaReal : $estimasiDenda;
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors print:break-inside-avoid">

                            {{-- 👤 FOTO & DATA USER --}}
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 border-2 border-white shadow-sm flex-shrink-0">
                                        @if($item->user?->photo)
                                            <img src="{{ asset('storage/' . $item->user->photo) }}"
                                                 class="w-full h-full object-cover"
                                                 alt="{{ $item->user->name }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-500 text-xs font-black">
                                                {{ strtoupper(substr($item->user->name ?? '?', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 text-sm leading-tight">
                                            {{ $item->user->name ?? '-' }}
                                        </span>
                                        <span class="text-[10px] text-blue-500 uppercase font-black tracking-tighter">
                                            {{ $item->user->anggota->kelas ?? 'Umum' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- 📚 FOTO & DATA BUKU --}}
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-14 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 shadow-sm border border-gray-100">
                                        @if($item->buku?->cover)
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
                                            {{ $item->buku->judul ?? '-' }}
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
                                    @if($tglAkhir)
                                        <span class="text-green-600">
                                            ✅ Selesai: {{ $tglAkhir->format('d/m/Y') }}
                                        </span>
                                    @endif
                                    @if($hariTelat > 0)
                                        <span class="text-[10px] text-red-500 font-bold mt-1">
                                            ⚠️ Telat {{ $hariTelat }} hari
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- 🏷️ STATUS BADGE (FIXED: Sesuai Permintaan) --}}
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
                                        <span class="px-3 py-1 bg-indigo-600 text-white text-[9px] font-black uppercase rounded-full shadow-sm">
                                            📩 Menunggu ACC
                                        </span>
                                        @break

                                    {{-- ✅ DENDA: SELALU tampilkan nominal (baik lunas maupun belum) --}}
                                    @case('denda')
                                        <div class="inline-flex flex-col items-center gap-1">
                                            @if($sudahDibayar)
                                                {{-- ✅ DENDA LUNAS --}}
                                                <span class="px-3 py-1 bg-green-100 text-green-700 text-[9px] font-black uppercase rounded-full border border-green-200">
                                                    ✅ Lunas
                                                </span>
                                            @else
                                                {{-- ❌ DENDA BELUM LUNAS --}}
                                                <span class="px-3 py-1 bg-red-500 text-white text-[9px] font-black uppercase rounded-full">
                                                    ⚠️ Belum
                                                </span>
                                            @endif
                                            {{-- 💰 NOMINAL DENDA: SELALU MUNCUL UNTUK STATUS DENDA --}}
                                            <span class="text-[9px] font-bold text-gray-700 whitespace-nowrap">
                                                Rp {{ number_format($totalDenda, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        @break

                                    {{-- ✅ SELESAI: HANYA BADGE, TANPA NOMINAL DENDA (Sesuai Request) --}}
                                    @case('selesai')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[9px] font-black uppercase rounded-full">
                                            ✅ Selesai
                                        </span>
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
                                <div class="flex justify-end gap-2 print:hidden">

                                    {{-- 🖨️ CETAK BUKTI --}}
                                    @if(in_array($item->status, ['dipinjam', 'denda', 'selesai', 'pengembalian_diajukan']))
                                    <a href="{{ route('peminjaman.bukti', $item->id) }}" target="_blank"
                                       class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2 rounded-lg transition-all"
                                       title="Cetak Bukti">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                    @endif

                                    {{-- ✅ / ❌ APPROVE PENGAJUAN --}}
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

                                    {{-- ✅ TERIMA PENGEMBALIAN --}}
                                    @if($item->status == 'pengembalian_diajukan')
                                        <form action="{{ route('pinjam.acc.kembali', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Terima pengembalian buku ini? Stok akan ditambahkan.')">
                                            @csrf
                                            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-sm">
                                                ✅ Terima
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
                                    <div class="text-gray-200 text-6xl mb-4 font-black italic">📭</div>
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
        <div class="mt-6 print:hidden">
            {{ $peminjaman->appends(request()->query())->links() }}
        </div>
        @endif

    </div>
</div>

{{-- ✅ PRINT CSS --}}
<style>
@media print {
    .print\:hidden { display: none !important; }
    .print\:break-inside-avoid { break-inside: avoid; }
    .text-gray-700, .text-gray-600 { color: #000 !important; }
    .bg-green-100 { background: #d4edda !important; -webkit-print-color-adjust: exact; }
    .bg-red-500 { background: #dc3545 !important; -webkit-print-color-adjust: exact; }
}
</style>
@endsection
