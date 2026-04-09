@extends('dashboard.app')

@section('title', 'Data Anggota')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">

        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Daftar Anggota Perpustakaan</h1>
                <p class="text-sm text-gray-500 font-medium mt-1">
                    Menampilkan seluruh siswa yang telah melakukan <span class="text-blue-600 font-bold text-xs uppercase tracking-wider">Registrasi Mandiri</span> ke dalam sistem.
                </p>
            </div>

            <div class="hidden md:flex gap-4">
                <div class="bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Anggota</p>
                    <p class="text-xl font-black text-blue-600">{{ $anggota->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-center w-12">No</th>
                            <th class="px-6 py-4">Informasi Profil</th>
                            <th class="px-6 py-4">Kontak / Email</th>
                            <th class="px-6 py-4">ID / NIS</th>
                            <th class="px-6 py-4">Status Akademik</th>
                            <th class="px-6 py-4 text-right">Manajemen</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($anggota as $index => $item)
                        <tr class="hover:bg-gray-50/80 transition-all group">
                            <td class="px-6 py-4 text-center text-gray-400 font-medium">
                                {{ $index + 1 }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-full overflow-hidden border-2 border-white shadow-sm ring-1 ring-gray-100 bg-gray-50 flex-shrink-0">
                                        @if(optional($item->user)->photo)
                                            <img src="{{ asset('storage/'.optional($item->user)->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr(optional($item->user)->name ?? 'A', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800 leading-tight">
                                            {{ optional($item->user)->name ?? 'Nama Tidak Ditemukan' }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 font-medium mt-0.5">Terdaftar sejak {{ $item->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500 font-medium">
                                    {{ optional($item->user)->email ?? '-' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <span class="text-xs font-mono font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                    {{ $item->nis ?? 'N/A' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 uppercase">
                                        {{ $item->kelas ?? '-' }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase text-center min-w-[40px]">
                                        {{ $item->jurusan ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="{{ route('petugas.anggota.edit', $item->id) }}"
                                       class="p-2 bg-white text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all border border-blue-100 shadow-sm" title="Edit Data">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>

                                    <form action="#"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus keanggotaan siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-white text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all border border-red-100 shadow-sm" title="Hapus Anggota">
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
                            <td colspan="6" class="py-24">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4 ring-8 ring-blue-50/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-gray-800 font-bold">Belum Ada Anggota Terdaftar</h3>
                                    <p class="text-gray-400 text-xs max-w-[250px] mx-auto mt-1">Siswa harus melakukan registrasi melalui portal pendaftaran untuk tampil di daftar ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
