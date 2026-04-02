@extends('dashboard.app')

@section('title', 'Data Anggota')

@section('content')
<div class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-7xl mx-auto">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-bold">Data Anggota</h1>

            <a href="#"
               class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 text-sm shadow">
               + Tambah Anggota
            </a>
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <table class="w-full text-sm">

                <!-- HEAD -->
                <thead class="bg-gray-200 text-gray-700 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Foto</th>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">NIS</th>
                        <th class="p-3 text-left">Kelas</th>
                        <th class="p-3 text-left">Jurusan</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody>

                    @forelse($anggota as $index => $item)
                    <tr class="border-b hover:bg-gray-50 transition">

                        <!-- NO -->
                        <td class="p-3">{{ $index + 1 }}</td>

                        <!-- FOTO -->
                        <td class="p-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                @if(optional($item->user)->photo)
                                    <img src="{{ asset('storage/'.optional($item->user)->photo) }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <span class="text-sm font-bold text-gray-500">
                                        {{ strtoupper(substr(optional($item->user)->name ?? 'A',0,1)) }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- DATA -->
                        <td class="p-3 font-medium">
                            {{ optional($item->user)->name ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ optional($item->user)->email ?? '-' }}
                        </td>

                        <td class="p-3">{{ $item->nis ?? '-' }}</td>
                        <td class="p-3">{{ $item->kelas ?? '-' }}</td>
                        <td class="p-3">{{ $item->jurusan ?? '-' }}</td>

                        <!-- AKSI -->
                        <td class="p-3 flex gap-2">

                            <a href="{{ route('petugas.anggota.edit', $item->id) }}"
                               class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                               Edit
                            </a>

                            <form action="#" method="POST"
                                  onsubmit="return confirm('Yakin hapus data?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">
                                    Hapus
                                </button>
                            </form>

                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="8" class="text-center p-10 text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-4xl">📭</span>
                                <p>Data anggota belum tersedia</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection
