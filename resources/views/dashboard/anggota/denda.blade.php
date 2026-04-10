@extends('layouts.app')
@section('title', 'Denda')

@section('content')
<div class="bg-gray-100 min-h-screen p-6">

  <div class="max-w-5xl mx-auto">

    {{-- NAV --}}
    <div class="bg-white rounded-xl shadow p-4 mb-6 flex gap-3">
        <a href="{{ route('dashboard.anggota') }}"
           class="px-4 py-2 bg-gray-100 rounded-lg text-sm">
            Dashboard
        </a>

        <a href="{{ route('anggota.denda') }}"
           class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm">
            💰 Denda
        </a>
    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow p-6">

        <h3 class="font-semibold mb-4">Daftar Denda</h3>

        @forelse($denda as $item)
        <div class="flex justify-between items-center border-b py-4">

            <div>
                <h4 class="font-semibold">{{ $item->buku->judul }}</h4>
                <p class="text-sm text-gray-500">
                    Terlambat sejak: {{ $item->tgl_kembali }}
                </p>
            </div>

            <div class="flex gap-2">

            <div class="flex gap-2">

   {{-- AKSI --}}
<div class="flex gap-2">

    {{-- 💰 BELUM AJUKAN BAYAR --}}
    @if($item->status == 'denda' && !$item->is_paid)
    <form action="{{ route('pinjam.bayar.denda', $item->id) }}" method="POST">
        @csrf
        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
            💰 Ajukan Bayar
        </button>
    </form>
    @endif

    {{-- ⏳ MENUNGGU KONFIRMASI PETUGAS --}}
    @if($item->status == 'denda' && $item->is_paid)
        <span class="bg-amber-500 text-white px-3 py-1 rounded text-xs">
            ⏳ Menunggu ACC Petugas
        </span>
    @endif

    {{-- ✅ SUDAH LUNAS --}}
    @if($item->is_paid && $item->status != 'denda')
        <span class="bg-green-500 text-white px-3 py-1 rounded text-xs">
            ✅ Lunas
        </span>
    @endif

</div>

</div>

            </div>

        </div>
        @empty
        <p class="text-gray-500">Tidak ada denda</p>
        @endforelse

    </div>

  </div>

</div>
@endsection
