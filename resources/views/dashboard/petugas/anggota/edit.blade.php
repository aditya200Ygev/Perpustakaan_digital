@extends('dashboard.app')

@section('title', 'Edit Anggota')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-3xl mx-auto">

        {{-- BREADCRUMB / BACK BUTTON --}}
        <a href="{{ route('petugas.anggota') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-blue-600 transition-colors mb-6 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="text-sm font-medium text-gray-500 group-hover:text-blue-600">Daftar Anggota</span>
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- HEADER FORM --}}
            <div class="p-6 border-b border-gray-50 bg-white flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Edit Data Siswa</h2>
                    <p class="text-sm text-gray-500 font-medium">Perbarui informasi profil dan akademik anggota.</p>
                </div>
                <div class="hidden md:block">
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full border border-blue-100 uppercase tracking-widest">Update Mode</span>
                </div>
            </div>

            <div class="p-8">
                <form action="{{ route('petugas.anggota.update', $anggota->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- NAMA --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Nama Lengkap Siswa</label>
                            <input type="text" name="name" value="{{ old('name', $anggota->user->name) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                                placeholder="Masukkan nama lengkap" required>
                        </div>

                        {{-- EMAIL --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $anggota->user->email) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                                placeholder="nama@sekolah.com" required>
                        </div>

                        {{-- NIS --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Nomor Induk Siswa (NIS)</label>
                            <input type="text" name="nis" value="{{ old('nis', $anggota->nis) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono tracking-widest focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                                placeholder="00123456" required>
                        </div>

                        {{-- KELAS --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Kelas</label>
                            <input type="text" name="kelas" value="{{ old('kelas', $anggota->kelas) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                                placeholder="Contoh: XII" required>
                        </div>

                        {{-- JURUSAN --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Jurusan</label>
                            <input type="text" name="jurusan" value="{{ old('jurusan', $anggota->jurusan) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                                placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                        </div>

                        {{-- FOTO SECTION --}}
                        <div class="flex flex-col gap-4">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Foto Profil</label>
                            <div class="flex items-center gap-5">
                                <div class="w-16 h-16 rounded-2xl overflow-hidden border-2 border-white shadow-md ring-1 ring-gray-100 bg-gray-100 flex-shrink-0">
                                    @if(optional($anggota->user)->photo)
                                        <img src="{{ asset('storage/'.optional($anggota->user)->photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($anggota->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="photo"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                                    <p class="text-[9px] text-gray-400 mt-1.5 ml-1 italic">*Biarkan kosong jika tidak ingin mengubah foto.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="flex items-center justify-end gap-3 pt-8 border-t border-gray-50">
                        <a href="{{ route('petugas.anggota') }}"
                           class="px-6 py-2.5 text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">
                            Batalkan
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-100 transition-all active:scale-95 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
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
