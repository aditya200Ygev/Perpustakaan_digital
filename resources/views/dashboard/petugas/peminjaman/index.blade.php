@extends('dashboard.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="bg-gray-50 min-h-screen p-4 md:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Data <span class="text-blue-600">Peminjaman</span></h1>
                <p class="text-gray-500 text-sm mt-1">Kelola pengajuan, peminjaman aktif, dan pengembalian buku.</p>
            </div>
        </div>

        {{-- FILTER LAPORAN --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Cari Data</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama siswa atau judul buku..."
                           class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none border transition-all text-sm font-bold">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Status</label>
                    <select name="status" class="border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none border text-sm bg-white font-bold">
                        <option value="">Semua Status</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Menunggu ACC</option>
                        <option value="denda" {{ request('status') == 'denda' ? 'selected' : '' }}>Denda</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

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
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Peminjam</th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Buku</th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Durasi</th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Status</th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($peminjaman as $item)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            {{-- FOTO & DATA USER --}}
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 border-2 border-white shadow-sm flex-shrink-0">
                                        {{-- Perbaikan: Cek path foto pada user object --}}
                                       @if($item->user && $item->user->photo)
                <img src="{{ asset('storage/' . $item->user->photo) }}" class="w-full h-full object-cover">
            @else
                                            <div class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-500 text-xs font-black">
                                                {{ substr($item->user->name ?? '?', 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 text-sm leading-tight">{{ optional($item->user)->name ?? '-' }}</span>
                                        <span class="text-[10px] text-blue-500 uppercase font-black tracking-tighter">
                                            Kelas: {{ $item->user->anggota->kelas ?? 'Umum' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- FOTO & DATA BUKU --}}
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-14 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 shadow-sm border border-gray-100">
                                        @if($item->buku && $item->buku->cover)
                                            <img src="{{ asset('storage/'.$item->buku->cover) }}" class="w-full h-full object-cover" alt="Cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[8px] font-black text-gray-300 uppercase p-1 text-center leading-none">No Cover</div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800 text-sm line-clamp-1">{{ optional($item->buku)->judul ?? '-' }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $item->jumlah ?? 1 }} Buku</span>
                                    </div>
                                </div>
                            </td>

                            <td class="p-5">
                                <div class="flex flex-col text-xs font-bold">
                                    <span class="text-gray-600">P: {{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</span>
                                    <span class="text-red-500 italic">K: {{ \Carbon\Carbon::parse($item->hari_akhir)->format('d/m/Y') }}</span>
                                </div>
                            </td>

                            <td class="p-5 text-center">
                                @if($item->status == 'diajukan')
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-[9px] font-black uppercase rounded-full">🔔 Diajukan</span>
                                @elseif($item->status == 'dipinjam')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[9px] font-black uppercase rounded-full">📖 Dipinjam</span>
                                @elseif($item->status == 'dikembalikan')
                                    <span class="px-3 py-1 bg-indigo-600 text-white text-[9px] font-black uppercase rounded-full shadow-sm">📩 Menunggu ACC</span>
                                @elseif($item->status == 'denda')
                                    <div class="inline-flex flex-col gap-1">
                                        <span class="px-3 py-1 bg-red-500 text-white text-[9px] font-black uppercase rounded-full">⚠️ Denda</span>
                                        <span class="text-[8px] font-black uppercase {{ $item->is_paid ? 'text-green-600' : 'text-red-400' }}">
                                            {{ $item->is_paid ? 'Lunas' : 'Belum Bayar' }}
                                        </span>
                                    </div>
                                @elseif($item->status == 'selesai')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-[9px] font-black uppercase rounded-full">✅ Selesai</span>
                                @elseif($item->status == 'ditolak')
                                    <span class="px-3 py-1 bg-gray-100 text-gray-400 text-[9px] font-black uppercase rounded-full">Ditolak</span>
                                @endif
                            </td>

                            <td class="p-5">
                                <div class="flex justify-end gap-2">
                                    {{-- TOMBOL CETAK BUKTI --}}
                                    @if(in_array($item->status, ['dipinjam', 'denda', 'selesai', 'dikembalikan']))
                                    <a href="{{ route('peminjaman.bukti', $item->id) }}"
                                       class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2 rounded-lg transition-all"
                                       title="Cetak Bukti">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                    @endif

                                    {{-- AKSI PERSETUJUAN --}}
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

                                    {{-- AKSI TERIMA PENGEMBALIAN --}}
                                    @if($item->status == 'dikembalikan')
                                        <form action="{{ route('pinjam.acc.kembali', $item->id) }}" method="POST">
                                            @csrf
                                            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                                                Terima Buku
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
                                    <p class="text-gray-400 font-medium italic text-sm tracking-widest uppercase">Tidak ada data peminjaman ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($peminjaman, 'links'))
        <div class="mt-6">
            {{ $peminjaman->appends(request()->query())->links() }}
        </div>
        @endif

    </div>
</div>
@endsection
