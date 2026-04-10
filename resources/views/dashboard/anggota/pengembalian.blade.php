@extends('layouts.app')
@section('title', 'Pengembalian Buku')

@section('content')
<div class="bg-gray-100 min-h-screen p-6">

  <div class="max-w-6xl mx-auto">

    {{-- NAV --}}
    <div class="bg-white rounded-xl shadow p-4 mb-6 flex gap-3 flex-wrap">
        <a href="{{ route('dashboard.anggota') }}"
           class="px-4 py-2 bg-gray-100 rounded-lg text-sm">
            Dashboard
        </a>

        <a href="{{ route('anggota.pengembalian') }}"
           class="px-4 py-2 bg-green-700 text-white rounded-lg text-sm">
            🔄 Pengembalian
        </a>
    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow p-6">

        <h3 class="font-semibold mb-4">Ajukan Pengembalian Buku</h3>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- ✅ FILTER VIEW: Hanya proses item dengan status yang diinginkan --}}
        @forelse($data->whereIn('status', ['dipinjam', 'pengembalian_diajukan']) as $item)
        <div class="flex items-center justify-between border-b py-4">

            {{-- INFO --}}
            <div class="flex gap-4">
                <img src="{{ $item->buku->cover ? Storage::url($item->buku->cover) : '/img/book.png' }}"
                     class="w-16 rounded">

                <div>
                    <h4 class="font-semibold">{{ $item->buku->judul }}</h4>
                    <p class="text-sm text-gray-500">
                        Batas: {{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d M Y') }}
                    </p>
                </div>
            </div>

            {{-- AKSI --}}
            <div class="flex gap-2 items-center">

                {{-- ✅ AJUKAN KEMBALI (Hanya jika dipinjam) --}}
                @if($item->status == 'dipinjam')
                    <form action="{{ route('pinjam.ajukan.kembali', $item->id) }}"
                          method="POST"
                          onsubmit="return confirm('Ajukan pengembalian buku ini?')"
                          class="inline">
                        @csrf
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition">
                            📤 Ajukan
                        </button>
                    </form>
                @endif

                {{-- ⏳ MENUNGGU ACC --}}
                @if($item->status == 'pengembalian_diajukan')
                    <span class="bg-yellow-500 text-white px-3 py-1 rounded text-xs">
                        ⏳ Menunggu ACC
                    </span>
                @endif

            </div>

        </div>
        @empty
            <p class="text-gray-500 text-sm text-center py-8">
                Tidak ada buku untuk dikembalikan
            </p>
        @endforelse

    </div>

  </div>

</div>
@endsection
