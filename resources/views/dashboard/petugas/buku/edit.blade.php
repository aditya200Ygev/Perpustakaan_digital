@extends('dashboard.app')

@section('title', 'Edit Buku')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-bold mb-6">Edit Buku</h2>

        <form action="{{ route('petugas.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- JUDUL -->
            <div>
                <label class="block text-sm font-medium mb-1">Judul Buku</label>
                <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                @error('judul')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <!-- PENULIS -->
            <div>
                <label class="block text-sm font-medium mb-1">Penulis</label>
                <input type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                @error('penulis')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <!-- PENERBIT -->
            <div>
                <label class="block text-sm font-medium mb-1">Penerbit</label>
                <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                @error('penerbit')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <!-- TAHUN -->
            <div>
                <label class="block text-sm font-medium mb-1">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                @error('tahun_terbit')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <!-- KATEGORI -->
            <div>
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="kategori_id"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}"
                            {{ old('kategori_id', $buku->kategori_id) == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <!-- DESKRIPSI -->
            <div>
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <!-- STOK -->
            <div>
                <label class="block text-sm font-medium mb-1">Stok Buku</label>
                <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                @error('stok')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <!-- COVER LAMA -->
            <div>
                <label class="block text-sm font-medium mb-2">Cover Saat Ini</label>

                @if($buku->cover)
                    <img src="{{ asset('storage/'.$buku->cover) }}"
                         class="w-24 h-32 object-cover rounded shadow">
                @else
                    <p class="text-sm text-gray-500">Tidak ada cover</p>
                @endif
            </div>

            <!-- GANTI COVER -->
            <div>
                <label class="block text-sm font-medium mb-1">Ganti Cover</label>
                <input type="file" name="cover"
                    class="w-full border rounded-lg px-3 py-2 bg-white">
                @error('cover')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between items-center pt-4">

                <a href="{{ route('petugas.buku') }}"
                   class="text-sm text-gray-600 hover:underline">
                   ← Kembali
                </a>

                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 shadow">
                    ✏️ Update
                </button>

            </div>

        </form>

    </div>

</div>
@endsection
