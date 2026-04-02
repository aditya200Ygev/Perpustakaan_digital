@extends('dashboard.app')

@section('title', 'Tambah Buku')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-bold mb-6">Tambah Buku</h2>

        {{-- ✅ TAMPILKAN ERROR --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('petugas.buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- JUDUL -->
            <div>
                <label class="block text-sm font-medium mb-1">Judul Buku</label>
                <input type="text" name="judul" value="{{ old('judul') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200"
                    placeholder="Masukkan judul buku" required>
            </div>

            <!-- PENULIS -->
            <div>
                <label class="block text-sm font-medium mb-1">Penulis</label>
                <input type="text" name="penulis" value="{{ old('penulis') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200"
                    placeholder="Nama penulis" required>
            </div>

            <!-- PENERBIT -->
            <div>
                <label class="block text-sm font-medium mb-1">Penerbit</label>
                <input type="text" name="penerbit" value="{{ old('penerbit') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200"
                    placeholder="Nama penerbit" required>
            </div>

            <!-- TAHUN -->
            <div>
                <label class="block text-sm font-medium mb-1">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200"
                    placeholder="2024" required>
            </div>

            <!-- KATEGORI (WAJIB) -->
           <div>
    <label class="block text-sm font-medium mb-1">Kategori</label>
    <select name="kategori_id"
        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200"
        required>

        <option value="">-- Pilih Kategori --</option>

        @foreach ($kategoris as $kategori)
            <option value="{{ $kategori->id }}"
                {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                {{ $kategori->nama }}
            </option>
        @endforeach

    </select>
</div>

            <!-- DESKRIPSI -->
            <div>
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200"
                    placeholder="Deskripsi buku">{{ old('deskripsi') }}</textarea>
            </div>

            <!-- STOK -->
            <div>
                <label class="block text-sm font-medium mb-1">Stok Buku</label>
                <input type="number" name="stok" value="{{ old('stok') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200"
                    placeholder="Contoh: 10" required>
            </div>

            <!-- COVER -->
            <div>
                <label class="block text-sm font-medium mb-1">Cover Buku</label>
                <input type="file" name="cover"
                    class="w-full border rounded-lg px-3 py-2 bg-white">
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between items-center pt-4">

                <a href="{{ route('petugas.buku') }}"
                   class="text-sm text-gray-600 hover:underline">
                   ← Kembali
                </a>

                <button type="submit"
                    class="bg-green-700 text-white px-5 py-2 rounded-lg hover:bg-green-800 shadow">
                    💾 Simpan
                </button>

            </div>

        </form>

    </div>

</div>
@endsection
