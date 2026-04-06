@extends('layouts.app')
@section('title', 'Dashboard — Anggota')

@section('content')
<div class="bg-gray-100 min-h-screen p-6">

  <div class="max-w-6xl mx-auto">

    {{-- 🔥 NAVIGASI --}}
    <div class="bg-white rounded-xl shadow p-4 mb-6 flex gap-3 flex-wrap">
        <a href="#" class="px-4 py-2 bg-green-700 text-white rounded-lg text-sm">
            Dashboard
        </a>

        <a href="#"
           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">
            📚 Peminjaman
        </a>

        <a href="#"
           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">
            🔄 Pengembalian
        </a>

        <a href="#"
           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">
            💰 Denda
        </a>

        <a href="#"
           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">
            🕘 Riwayat Transaksi
        </a>
    </div>

    {{-- GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      {{-- PROFILE --}}
      <div class="bg-white rounded-xl shadow p-6 text-center">

        <div class="w-24 h-24 rounded-full mx-auto overflow-hidden">
          @if($user->photo)
            <img src="{{ Storage::url($user->photo) }}" class="w-full h-full object-cover">
          @else
            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
              <span class="text-3xl text-gray-500">
                {{ strtoupper(substr($user->name,0,1)) }}
              </span>
            </div>
          @endif
        </div>

        <h3 class="mt-4 font-semibold text-lg">{{ $user->name }}</h3>
        <p class="text-sm text-gray-500">
          ID: {{ optional($user->anggota)->nis ?? '-' }}
        </p>

        <div class="mt-4 bg-gray-100 rounded-lg p-3 flex justify-between items-center">
          <span class="text-sm text-gray-600">Total Buku Dipinjam</span>
          <span class="font-bold">{{ $totalPinjam ?? 0 }}</span>
        </div>

        <a href="{{ route('profile.edit') }}"
           class="mt-4 inline-block bg-green-800 text-white px-4 py-2 rounded-lg hover:bg-green-900">
           Edit / Tambah Foto
        </a>
      </div>

      {{-- DATA DIRI --}}
      <div class="bg-white rounded-xl shadow p-6 md:col-span-2">
        <h3 class="font-semibold mb-4">DATA DIRI</h3>

        <div class="bg-gray-100 rounded-lg p-4 space-y-2 text-sm">
          <div class="flex justify-between">
            <span>Nama</span>
            <span>{{ $user->name }}</span>
          </div>

          <div class="flex justify-between">
            <span>Email</span>
            <span>{{ $user->email }}</span>
          </div>

          <div class="flex justify-between">
            <span>No Telp</span>
            <span>{{ $user->no_telp ?? '-' }}</span>
          </div>

          <div class="flex justify-between">
            <span>ID</span>
            <span>{{ optional($user->anggota)->nis ?? '-' }}</span>
          </div>

          <div class="flex justify-between">
            <span>Kelas</span>
            <span>{{ optional($user->anggota)->kelas ?? '-' }}</span>
          </div>
        </div>

        <div class="text-right mt-4">
          <a href="{{ route('profile.edit') }}"
             class="inline-block bg-green-800 text-white px-4 py-2 rounded-lg hover:bg-green-900">
             Edit Profile
          </a>
        </div>
      </div>

      {{-- SEDANG DIPINJAM --}}
      <div class="bg-white rounded-xl shadow p-6 md:col-span-3">
        <h3 class="font-semibold mb-4">Sedang Dipinjam</h3>

        @forelse($dipinjam as $item)
        <div class="flex gap-4 mb-4">
          <img src="{{ $item->buku->cover ? Storage::url($item->buku->cover) : '/img/book.png' }}"
               class="w-16 rounded">

          <div class="text-sm">
            <h4 class="font-semibold">{{ $item->buku->judul }}</h4>
            <p class="text-gray-500">Batas Waktu : {{ $item->tgl_kembali }}</p>

            <span class="inline-block mt-1 bg-blue-600 text-white text-xs px-2 py-1 rounded">
              Dipinjam
            </span>
          </div>
        </div>
        @empty
        <p class="text-gray-500 text-sm">Tidak ada buku dipinjam</p>
        @endforelse
      </div>

      {{-- RIWAYAT --}}
      <div class="bg-white rounded-xl shadow p-6 md:col-span-3">
        <h3 class="font-semibold mb-4">Riwayat Peminjaman</h3>

        @forelse($riwayat as $item)
        <div class="flex gap-4 mb-4">
          <img src="{{ $item->buku->cover ? Storage::url($item->buku->cover) : '/img/book.png' }}"
               class="w-16 rounded">

          <div class="text-sm">
            <h4 class="font-semibold">{{ $item->buku->judul }}</h4>
            <p class="text-gray-500">Batas Waktu : {{ $item->tgl_kembali }}</p>

            <span class="inline-block mt-1 bg-green-500 text-white text-xs px-2 py-1 rounded">
              {{ ucfirst($item->status) }}
            </span>

            <p class="text-xs text-gray-400 mt-1">{{ $item->updated_at }}</p>
          </div>
        </div>
        @empty
        <p class="text-gray-500 text-sm">Belum ada riwayat</p>
        @endforelse
      </div>

    </div>
  </div>
</div>
@endsection
