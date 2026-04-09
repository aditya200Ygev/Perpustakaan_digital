    @extends('layouts.app')
    @section('title', 'Dashboard Anggota')

    @section('content')
    <div class="bg-gray-50 min-h-screen py-10 px-4 sm:px-6">
        <div class="max-w-6xl mx-auto">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight uppercase">Profile <span class="text-blue-600">Anggota</span></h1>
                    <p class="text-gray-500 text-sm mt-1">Selamat datang kembali, silakan kelola aktivitas literasi Anda.</p>
                </div>

                {{-- NAVIGASI QUICK LINKS --}}
                <div class="flex bg-white p-1.5 rounded-2xl shadow-sm border border-gray-100 items-center gap-1">
                    <a href="{{ route('dashboard.anggota') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest transition shadow-md shadow-emerald-100">
                        🏠 Beranda
                    </a>
                    <a href="{{ route('anggota.pengembalian') }}" class="px-4 py-2 text-gray-500 hover:text-emerald-600 rounded-xl text-xs font-bold uppercase tracking-widest transition hover:bg-emerald-50">
                        🔄 Kembali
                    </a>
                    <a href="{{ route('anggota.denda') }}" class="px-4 py-2 text-gray-500 hover:text-emerald-600 rounded-xl text-xs font-bold uppercase tracking-widest transition hover:bg-emerald-50">
                        💰 Denda
                    </a>
                    <a href="#" class="px-4 py-2 text-gray-500 hover:text-emerald-600 rounded-xl text-xs font-bold uppercase tracking-widest transition hover:bg-emerald-50">
                        🕘 Riwayat
                    </a>
                </div>
            </div>

            {{-- TOP GRID: PROFILE & DATA DIRI --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">

                {{-- PROFILE CARD (Col 4) --}}
                <div class="lg:col-span-4 bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 p-8 flex flex-col items-center border border-gray-100">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-emerald-600 to-teal-500 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                        <div class="relative w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg">
                            @if($user->photo)
                                <img src="{{ Storage::url($user->photo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-emerald-50 flex items-center justify-center">
                                    <span class="text-4xl font-black text-emerald-300">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="text-center mt-6">
                        <h3 class="font-black text-xl text-gray-900 uppercase tracking-tight">{{ $user->name }}</h3>
                        <p class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full uppercase tracking-[0.2em] mt-2 inline-block">
                            ID: {{ optional($user->anggota)->nis ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="w-full mt-8 grid grid-cols-1 gap-3">
                        <div class="bg-gray-50 rounded-2xl p-4 flex justify-between items-center border border-gray-100">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Aktif Pinjam</span>
                            <span class="text-xl font-black text-emerald-700">{{ $totalPinjam ?? 0 }} <small class="text-[10px] font-normal text-gray-400 uppercase">Buku</small></span>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="text-center bg-gray-900 text-white px-4 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-emerald-600 transition-all shadow-lg shadow-gray-200">
                            Edit Profil / Foto
                        </a>
                    </div>
                </div>

                {{-- DATA DIRI CARD (Col 8) --}}
                <div class="lg:col-span-8 bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 p-8 md:p-10 border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-10 opacity-[0.03] pointer-events-none">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>

                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em]">Informasi Pribadi</h3>
                        <span class="h-1 w-12 bg-emerald-500 rounded-full"></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <div class="space-y-1 border-b border-gray-50 pb-3">
                            <label class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Email Terdaftar</label>
                            <p class="font-bold text-gray-800 break-all">{{ $user->email }}</p>
                        </div>
                        <div class="space-y-1 border-b border-gray-50 pb-3">
                            <label class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Kontak</label>
                            <p class="font-bold text-gray-800">{{ $user->no_telp ?? '-' }}</p>
                        </div>
                        <div class="space-y-1 border-b border-gray-50 pb-3">
                            <label class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">NIS Anggota</label>
                            <p class="font-bold text-gray-800">{{ optional($user->anggota)->nis ?? '-' }}</p>
                        </div>
                        <div class="space-y-1 border-b border-gray-50 pb-3">
                            <label class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Kelas & Unit</label>
                            <p class="font-bold text-gray-800">{{ optional($user->anggota)->kelas ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BOTTOM GRID: STATUS PINJAM --}}
            <div class="grid grid-cols-1 gap-8">

                {{-- SEDANG DIPINJAM --}}
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 p-8 border border-gray-100">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center">
                            <span class="text-xl">📖</span>
                        </div>
                        <h3 class="font-black text-gray-900 uppercase tracking-tighter text-lg">Buku Sedang <span class="text-blue-600">Dipinjam</span></h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($dipinjam as $item)
                        <div class="group relative bg-gray-50 rounded-[2rem] p-5 border border-transparent hover:border-blue-100 hover:bg-white transition-all duration-300 shadow-sm hover:shadow-xl hover:shadow-blue-100/50">
                            <div class="flex gap-5">
                                <div class="w-20 h-28 flex-shrink-0 relative overflow-hidden rounded-xl shadow-md">
                                    <img src="{{ $item->buku->cover ? Storage::url($item->buku->cover) : asset('img/book.png') }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>

                                <div class="flex flex-col justify-between py-1">
                                    <div>
                                        <h4 class="font-black text-gray-900 leading-tight text-sm uppercase line-clamp-2">{{ $item->buku->judul }}</h4>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase mt-2 tracking-widest">Deadline</p>
                                        <p class="text-xs font-black text-red-500 italic">{{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d M Y') }}</p>
                                    </div>
                                    <div>
                                        <span class="inline-block px-3 py-1 bg-blue-600 text-white text-[8px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-blue-100">
                                            PINJAMAN AKTIF
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full py-16 text-center bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200">
                            <span class="text-4xl mb-4 block">📭</span>
                            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest italic px-4">Rak buku Anda kosong, yuk ke perpustakaan!</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- RIWAYAT --}}
                <div class="bg-gray-900 rounded-[2.5rem] shadow-2xl p-8 md:p-10 text-white overflow-hidden relative">
                    <div class="absolute bottom-0 right-0 p-10 opacity-10 pointer-events-none transform translate-y-1/4 translate-x-1/4">
                        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
                    </div>

                    <h3 class="font-black text-white uppercase tracking-[0.2em] text-sm mb-8 flex items-center gap-3">
                        <span class="w-8 h-1 bg-emerald-500 rounded-full"></span>
                        Riwayat Terakhir
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($riwayat as $item)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all backdrop-blur-sm">
                            <div class="flex gap-4 items-center">
                                <div class="w-10 h-14 bg-white/10 rounded overflow-hidden flex-shrink-0">
                                    <img src="{{ $item->buku->cover ? Storage::url($item->buku->cover) : asset('img/book.png') }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs text-white uppercase tracking-tight line-clamp-1">{{ $item->buku->judul }}</h4>
                                    <p class="text-[9px] text-gray-400 mt-1 uppercase tracking-widest font-medium">📅 {{ $item->updated_at->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <span class="px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $item->status == 'kembali' || $item->status == 'selesai' ? 'bg-emerald-500 text-gray-900' : 'bg-red-500 text-white' }}">
                                {{ $item->status }}
                            </span>
                        </div>
                        @empty
                        <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em] py-4 italic">Belum ada aktivitas tercatat.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endsection
