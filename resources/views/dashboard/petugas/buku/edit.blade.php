@extends('dashboard.app')

@section('title', 'Edit Buku')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-3xl mx-auto">

        {{-- BREADCRUMB / BACK BUTTON --}}
        <a href="{{ route('petugas.buku') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 transition-colors mb-6 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="text-sm font-medium">Kembali ke Koleksi</span>
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- HEADER FORM --}}
            <div class="p-6 border-b border-gray-50 bg-white flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-800">Edit Informasi Buku</h2>
                    <p class="text-sm text-gray-500">Perbarui detail koleksi "{{ $buku->judul }}"</p>
                </div>
                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full border border-blue-100">ID: #{{ $buku->id }}</span>
            </div>

            <div class="p-8">
                <form action="{{ route('petugas.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- JUDUL --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Judul Lengkap Buku</label>
                            <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all @error('judul') border-red-400 @enderror"
                                required>
                            @error('judul') <p class="text-red-500 text-[10px] mt-1 ml-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- PENULIS --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Penulis</label>
                            <input type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all @error('penulis') border-red-400 @enderror"
                                required>
                            @error('penulis') <p class="text-red-500 text-[10px] mt-1 ml-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- PENERBIT --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Penerbit</label>
                            <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all @error('penerbit') border-red-400 @enderror"
                                required>
                            @error('penerbit') <p class="text-red-500 text-[10px] mt-1 ml-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- TAHUN --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                                required>
                        </div>

                        {{-- KATEGORI --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Kategori</label>
                            <div class="relative">
                                <select name="kategori_id"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer"
                                    required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $buku->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- STOK --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Stok Buku</label>
                            <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                                required>
                        </div>

                        {{-- COVER SECTION --}}
                        <div class="md:col-span-1">
                             <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Cover Saat Ini</label>
                             <div class="w-32 h-44 rounded-xl overflow-hidden border-2 border-gray-100 shadow-sm bg-gray-50">
                                @if($buku->cover)
                                    <img src="{{ asset('storage/'.$buku->cover) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                             </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Ganti Cover Baru</label>
                            <div class="flex flex-col gap-2">
                                <input type="file" name="cover"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer transition-all">
                                <p class="text-[10px] text-gray-400 italic font-medium ml-1">*Biarkan kosong jika tidak ingin mengganti cover.</p>
                            </div>
                            @error('cover') <p class="text-red-500 text-[10px] mt-1 ml-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- DESKRIPSI --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Ringkasan / Deskripsi</label>
                            <textarea name="deskripsi" rows="4"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none"
                                placeholder="Tulis sinopsis singkat buku...">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                        </div>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-50">
                        <a href="{{ route('petugas.buku') }}" class="px-6 py-2.5 text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">
                            Batalkan
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-100 transition-all active:scale-95 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
