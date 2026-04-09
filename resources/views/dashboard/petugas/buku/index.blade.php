@extends('dashboard.app')

@section('title', 'Katalog Buku')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER SECTION --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Katalog Buku</h1>
                <p class="text-sm text-gray-500">Kelola koleksi buku perpustakaan Anda di sini.</p>
            </div>

            <a href="{{ route('petugas.buku.create') }}"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md shadow-blue-100 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Buku Baru
            </a>
        </div>

        {{-- FILTER & SEARCH SECTION --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <form action="{{ route('petugas.buku') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">

                {{-- Filter Kategori --}}
                <div class="md:col-span-3">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">Kategori</label>
                    <select name="kategori_id" onchange="this.form.submit()"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all cursor-pointer font-bold">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Input Pencarian --}}
                <div class="md:col-span-7">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">Cari Buku</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5 pl-10 font-medium"
                            placeholder="Cari berdasarkan judul atau penulis...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="md:col-span-2 flex items-end gap-2">
                    <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-900 transition-all active:scale-95">
                        Cari
                    </button>
                    @if(request('kategori_id') || request('search'))
                        <a href="{{ route('petugas.buku') }}"
                           class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors border border-red-100" title="Reset Filter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- DATA TABLE SECTION --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-center w-12">No</th>
                            <th class="px-6 py-4">Informasi Buku</th>
                            <th class="px-6 py-4">Kategori & Penulis</th>
                            <th class="px-6 py-4 text-center">Tahun</th>
                            <th class="px-6 py-4 text-center">Stok</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($bukus as $index => $buku)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 text-center text-gray-400 text-xs font-medium">
                                {{ $bukus->firstItem() + $index }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-16 rounded-lg overflow-hidden shadow-sm border border-gray-100 flex-shrink-0 bg-gray-50">
                                        @if($buku->cover)
                                            <img src="{{ asset('storage/'.$buku->cover) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-[8px] text-gray-300 uppercase font-bold text-center p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                No Cover
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col max-w-xs">
                                        <span class="text-sm font-bold text-gray-800 leading-tight mb-1">{{ $buku->judul }}</span>
                                        <span class="text-[10px] text-gray-400 line-clamp-2 italic">{{ Str::limit($buku->deskripsi, 60) }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1.5">
                                    <span class="w-fit px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                        {{ $buku->kategori->nama ?? 'Uncategorized' }}
                                    </span>
                                    <span class="text-xs text-gray-600 font-medium flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $buku->penulis }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center text-sm font-bold text-gray-500">
                                {{ $buku->tahun_terbit }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($buku->stok > 0)
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-black text-gray-800 leading-none">{{ $buku->stok }}</span>
                                        <span class="text-[9px] text-green-500 font-bold uppercase tracking-tighter">Tersedia</span>
                                    </div>
                                @else
                                    <span class="px-2 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-600 border border-red-100">
                                        Habis
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('petugas.buku.edit', $buku->id) }}"
                                       class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors border border-amber-100 shadow-sm" title="Edit Buku">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('petugas.buku.delete', $buku->id) }}"
                                          method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors border border-red-100 shadow-sm" title="Hapus Buku">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-20">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <h3 class="text-gray-500 font-bold text-lg">Tidak Menemukan Buku</h3>
                                    <p class="text-gray-400 text-sm">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-8 flex justify-center">
            {{ $bukus->appends(request()->query())->links() }}
        </div>

    </div>
</div>

{{-- SweetAlert --}}
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false,
        borderRadius: '20px'
    });
</script>
@endif

@endsection
