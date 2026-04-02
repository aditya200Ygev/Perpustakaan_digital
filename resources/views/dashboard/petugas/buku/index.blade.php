@extends('dashboard.app')

@section('title', 'Data Buku')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-bold">Data Buku</h1>

            <a href="{{ route('petugas.buku.create') }}"
               class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 text-sm shadow">
               + Tambah Buku
            </a>
        </div>

        <!-- ALERT -->
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow overflow-x-auto">

            <table class="w-full text-sm">

                <!-- HEAD -->
                <thead class="bg-gray-200 text-gray-700 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Cover</th>
                        <th class="p-3 text-left">Judul</th>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3 text-left">Penulis</th>
                        <th class="p-3 text-left">Tahun</th>
                        <th class="p-3 text-left">Stok</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody>

                    @forelse($bukus as $index => $buku)
                    <tr class="border-b hover:bg-gray-50">

                        <!-- NO (aman untuk pagination) -->
                        <td class="p-3">
                            {{ $loop->iteration }}
                        </td>

                        <!-- COVER -->
                        <td class="p-3">
                            @if($buku->cover)
                                <img src="{{ asset('storage/'.$buku->cover) }}"
                                     class="w-12 h-16 object-cover rounded">
                            @else
                                <div class="w-12 h-16 bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                                    No Img
                                </div>
                            @endif
                        </td>

                        <!-- JUDUL -->
                        <td class="p-3 font-medium">
                            {{ $buku->judul }}
                            <p class="text-[10px] text-gray-400 mt-1">
                                {{ Str::limit($buku->deskripsi, 40) }}
                            </p>
                        </td>

                        <!-- KATEGORI -->
                        <td class="p-3">
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                {{ $buku->kategori->nama ?? '-' }}
                            </span>
                        </td>

                        <!-- PENULIS -->
                        <td class="p-3">{{ $buku->penulis }}</td>

                        <!-- TAHUN -->
                        <td class="p-3">{{ $buku->tahun_terbit }}</td>

                        <!-- STOK -->
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs rounded
                                {{ $buku->stok > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $buku->stok }}
                            </span>
                        </td>

                        <!-- AKSI -->
                        <td class="p-3 flex gap-2">

                            <a href="{{ route('petugas.buku.edit', $buku->id) }}"
                               class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                               Edit
                            </a>

                            <form action="{{ route('petugas.buku.delete', $buku->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin hapus buku ini?')">
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
                                <span class="text-4xl">📚</span>
                                <p>Belum ada data buku</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- PAGINATION (kalau nanti pakai paginate) -->
        <div class="mt-6">
            {{ $bukus->links() }}
        </div>

    </div>

</div>
@endsection
