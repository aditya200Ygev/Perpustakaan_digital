@extends('dashboard.app')

@section('title', 'Detail Buku')

@section('content')

<div class="p-6 bg-gray-100 min-h-screen">

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow">

        <h2 class="text-xl font-bold mb-4">{{ $buku->judul }}</h2>

        <p><b>Penulis:</b> {{ $buku->penulis }}</p>
        <p><b>Penerbit:</b> {{ $buku->penerbit }}</p>
        <p><b>Tahun:</b> {{ $buku->tahun_terbit }}</p>
        <p><b>Stok:</b> {{ $buku->stok }}</p>
        <p class="mt-2"><b>Deskripsi:</b> {{ $buku->deskripsi }}</p>

        {{-- ERROR --}}
        @if(session('error'))
            <div class="mt-4 p-3 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- TOMBOL PINJAM --}}
        <form action="{{ route('pinjam.store') }}" method="POST" class="mt-6">
            @csrf

            <input type="hidden" name="buku_id" value="{{ $buku->id }}">

            <button type="submit"
                class="bg-green-700 text-white px-5 py-2 rounded-lg hover:bg-green-800">

                🟢 PINJAM
            </button>

        </form>

    </div>

</div>

@endsection
