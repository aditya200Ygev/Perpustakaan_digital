@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')

@php use Illuminate\Support\Facades\Storage; @endphp

<div class="bg-gray-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        {{-- Header & Back Button --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight uppercase">Pengaturan <span class="text-blue-600">Profil</span></h1>
                <p class="text-gray-500 text-sm mt-1">Perbarui informasi pribadi dan foto profil Anda.</p>
            </div>
            <a href="/" class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-gray-900 transition-colors">
                <span>←</span> KEMBALI
            </a>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-8 md:p-12">
                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="mb-8 flex items-center gap-3 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-r-xl">
                            <span class="text-xl">✓</span>
                            <p class="text-sm font-bold uppercase tracking-wide">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-8 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl">
                            <p class="font-bold mb-2 uppercase text-xs tracking-widest">Terdapat Kesalahan:</p>
                            <ul class="text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-12">

                        {{-- KIRI: Foto Profil --}}
                        <div class="md:col-span-4 flex flex-col items-center border-b md:border-b-0 md:border-r border-gray-100 pb-8 md:pb-0 md:pr-8">
                            <div class="relative group">
                                <div class="w-40 h-40 rounded-full overflow-hidden ring-4 ring-gray-50 shadow-inner bg-gray-100">
                                    @if($user->photo)
                                        <img src="{{ Storage::url($user->photo) }}" id="preview" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div id="placeholder" class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <label for="photo-upload" class="absolute bottom-2 right-2 bg-blue-600 text-white p-3 rounded-full shadow-lg cursor-pointer hover:bg-blue-700 transition-all hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </label>
                                <input id="photo-upload" type="file" name="photo" class="hidden" onchange="previewImage(event)">
                            </div>
                            <p class="mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Format: JPG, PNG (Maks 2MB)</p>
                        </div>

                        {{-- KANAN: Form Detail --}}
                        <div class="md:col-span-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- NAMA --}}
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                           class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none border">
                                </div>

                                {{-- EMAIL --}}
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Alamat Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                           class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none border">
                                </div>

                                {{-- NO TELP --}}
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nomor Telepon</label>
                                    <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}"
                                           class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none border">
                                </div>

                                {{-- NIS --}}
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">NIS</label>
                                    <input type="text" name="nis" value="{{ old('nis', $user->anggota->nis ?? '') }}"
                                           class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none border">
                                </div>

                                {{-- KELAS (Read Only) --}}
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kelas</label>
                                    <div class="relative">
                                        <input type="text" value="{{ $user->anggota->kelas ?? '' }}" readonly
                                               class="w-full bg-gray-50 border-gray-100 text-gray-500 rounded-xl px-4 py-3 cursor-not-allowed border outline-none">
                                        <input type="hidden" name="kelas" value="{{ $user->anggota->kelas ?? '' }}">
                                        <span class="absolute right-4 top-3.5 text-gray-300">🔒</span>
                                    </div>
                                </div>

                                {{-- JURUSAN --}}
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jurusan</label>
                                    <input type="text" name="jurusan" value="{{ old('jurusan', $user->anggota->jurusan ?? '') }}"
                                           class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none border">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Form --}}
                <div class="bg-gray-50 px-8 py-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-gray-400 italic font-medium">Terakhir diperbarui: {{ Auth::user()->updated_at->diffForHumans() }}</p>
                    <div class="flex gap-3 w-full md:w-auto">
                        <a href="/" class="flex-1 md:flex-none text-center px-8 py-3 bg-white border border-gray-200 rounded-full text-xs font-black uppercase tracking-widest hover:bg-gray-50 transition-all">
                            Batal
                        </a>
                        <button type="submit" class="flex-1 md:flex-none px-10 py-3 bg-gray-900 text-white rounded-full text-xs font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-gray-200">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview');
            var placeholder = document.getElementById('placeholder');

            if(output) {
                output.src = reader.result;
            } else {
                // Jika sebelumnya tidak ada foto, buat elemen img
                location.reload(); // Sederhananya reload untuk refresh state preview
            }
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
