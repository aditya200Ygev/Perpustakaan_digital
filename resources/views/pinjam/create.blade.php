@extends('layouts.app')

@section('title', 'Detail Buku - ' . $buku->judul)

@section('content')

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6">
    <div class="max-w-5xl mx-auto">

        {{-- Back Button --}}
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-xs font-black text-gray-400 hover:text-blue-600 transition-colors uppercase tracking-widest mb-8">
            <span>←</span> Kembali ke Koleksi
        </a>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100">
            <div class="grid lg:grid-cols-12 gap-0">

                {{-- KIRI: Visual Buku --}}
                <div class="lg:col-span-5 bg-gray-50 p-8 md:p-12 flex flex-col items-center justify-center border-r border-gray-50">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000"></div>
                        <img src="{{ $buku->cover ? Storage::url($buku->cover) : '/img/book.png' }}"
                             class="relative w-64 md:w-72 aspect-[3/4.5] object-cover rounded-xl shadow-2xl transform transition-transform duration-500 group-hover:scale-[1.02]">
                    </div>

                    <div class="mt-8 flex gap-3">
                        @if($buku->stok > 0)
                            <span class="px-4 py-1.5 bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest rounded-full">Tersedia: {{ $buku->stok }} Unit</span>
                        @else
                            <span class="px-4 py-1.5 bg-red-100 text-red-700 text-[10px] font-black uppercase tracking-widest rounded-full">Stok Habis</span>
                        @endif
                        <span class="px-4 py-1.5 bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-widest rounded-full">ID: #{{ str_pad($buku->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>

                {{-- KANAN: Informasi & Form --}}
                <div class="lg:col-span-7 p-8 md:p-12">
                    <div class="mb-8">
                        <h1 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight mb-4 tracking-tighter uppercase">{{ $buku->judul }}</h1>
                        <div class="flex flex-wrap gap-y-2 gap-x-6 text-sm">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Penulis</span>
                                <span class="font-bold text-gray-800">{{ $buku->penulis }}</span>
                            </div>
                            <div class="flex flex-col border-l border-gray-200 pl-6">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Penerbit</span>
                                <span class="font-bold text-gray-800">{{ $buku->penerbit }}</span>
                            </div>
                            <div class="flex flex-col border-l border-gray-200 pl-6">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tahun</span>
                                <span class="font-bold text-gray-800">{{ $buku->tahun_terbit }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Sinopsis / Deskripsi</h3>
                        <p class="text-gray-600 leading-relaxed text-sm italic">
                            "{{ $buku->deskripsi }}"
                        </p>
                    </div>

                    <hr class="border-gray-100 mb-10">

                    {{-- Feedback Messages --}}
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 text-xs font-bold uppercase tracking-wide rounded-r-xl animate-pulse">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold uppercase tracking-wide rounded-r-xl">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Form Pinjam --}}
                    <form id="pinjamForm" action="{{ route('pinjam.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="buku_id" value="{{ $buku->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- TANGGAL --}}
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Tanggal Pinjam</label>
                                <input type="date" name="tgl_pinjam" required
                                       value="{{ date('Y-m-d') }}"
                                       class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none border transition-all text-sm font-bold text-gray-700">
                            </div>

                            {{-- JUMLAH --}}
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Jumlah Pinjam</label>
                                <select name="jumlah" required
                                        class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none border transition-all text-sm font-bold text-gray-700">
                                    @for($i = 1; $i <= min(36, $buku->stok); $i++)
                                        <option value="{{ $i }}">{{ $i }} Buku</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- ACTION BUTTON --}}
                        <div class="pt-4">
                            @if($buku->stok > 0)
                                <button type="button" onclick="confirmPinjam()"
                                        class="w-full md:w-auto px-12 py-4 bg-gray-900 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-blue-600 transition-all duration-300 shadow-xl shadow-gray-200 hover:shadow-blue-200">
                                    Konfirmasi Peminjaman <span>→</span>
                                </button>
                            @else
                                <button type="button" disabled
                                        class="w-full md:w-auto px-12 py-4 bg-gray-200 text-gray-400 rounded-full text-xs font-black uppercase tracking-[0.2em] cursor-not-allowed">
                                    Maaf, Stok Sedang Kosong
                                </button>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- MODAL / POPUP KONFIRMASI (Sederhana tapi Cantik) --}}
<script>
function confirmPinjam() {
    const swal = confirm('Apakah Anda yakin data peminjaman sudah benar? Buku ini wajib dijaga dengan baik.');
    if (swal) {
        document.getElementById('pinjamForm').submit();
    }
}
</script>

@endsection
