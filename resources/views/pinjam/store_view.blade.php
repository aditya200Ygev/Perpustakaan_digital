@extends('layouts.app')

@section('title', 'Konfirmasi Peminjaman')

@section('content')

<div class="p-6 bg-gray-100 min-h-screen">

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-bold mb-6">📄 Detail Peminjaman</h2>

        {{-- DATA --}}
        <div class="space-y-3">

            <div>
                <p class="text-sm text-gray-500">Nama Anggota</p>
                <p class="font-semibold">{{ auth()->user()->name }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Judul Buku</p>
                <p class="font-semibold">{{ $buku->judul }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Kategori</p>
                <p class="font-semibold">
                    {{ $buku->kategori->nama ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Pinjam</p>
                <p class="font-semibold">{{ now()->format('d-m-Y') }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Kembali</p>
                <p class="font-semibold">{{ now()->addDays(3)->format('d-m-Y') }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Stok Tersedia</p>
                <p class="font-semibold">
                    {{ $buku->stok > 0 ? $buku->stok : 'Habis' }}
                </p>
            </div>

        </div>

        {{-- ERROR --}}
        @if(session('error'))
            <div class="mt-4 p-3 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- FORM --}}
        <form id="pinjamForm" action="{{ route('pinjam.store') }}" method="POST" class="mt-6">
            @csrf

            <input type="hidden" name="buku_id" value="{{ $buku->id }}">

            {{-- JUMLAH --}}
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Jumlah Pinjam
                </label>

                <select name="jumlah" class="w-full border rounded p-2" required>
                    @for($i = 1; $i <= min(36, $buku->stok); $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <button type="button"
                onclick="confirmPinjam()"
                @disabled($buku->stok < 1)
                class="px-5 py-2 rounded-lg text-white
                {{ $buku->stok > 0 ? 'bg-green-700 hover:bg-green-800' : 'bg-gray-400 cursor-not-allowed' }}">

                🟢 Konfirmasi Pinjam
            </button>

        </form>

    </div>

</div>

<script>
function confirmPinjam() {
    if (confirm('Yakin ingin meminjam buku ini?')) {
        document.getElementById('pinjamForm').submit();
    }
}
</script>

@endsection
