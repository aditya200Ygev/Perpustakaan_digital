@extends('layouts.app')

@section('title')
BUKU <br> PERPUSTAKAAN DIGITAL
@endsection

@section('content')

<!-- CARD INFO -->
<div class="bg-[#e5e5e5] py-10 px-16">
    <div class="bg-white rounded-2xl shadow-md p-6 flex items-center gap-6 w-full">

        <img src="/image/hantu.jpg"
             class="w-60 h-80 object-cover rounded-xl">

        <div>
            <h3 class="font-semibold text-base">
                Apa saja buku yang ada di perpustakaan digital?
            </h3>

            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                Koleksi buku lengkap mulai dari pendidikan, novel, hingga dongeng tersedia di sini.
            </p>

            <a href="#"
               class="mt-4 inline-block bg-black text-white text-xs px-5 py-2 rounded-lg hover:bg-gray-800">
                Baca Selengkapnya
            </a>
        </div>

    </div>
</div>

<!-- KATEGORI -->
<div class="bg-white py-6">
    <div class="flex justify-center gap-3 text-xs font-medium">

        <button class="px-5 py-2 bg-gray-200 rounded-full">PENDIDIKAN</button>
        <button class="px-5 py-2 bg-yellow-400 text-black rounded-full shadow">NOVEL</button>
        <button class="px-5 py-2 bg-gray-200 rounded-full">DONGENG</button>
        <button class="px-5 py-2 bg-gray-200 rounded-full">SEARCH</button>

    </div>
</div>

<!-- GRID -->
<div class="px-16 pb-14 bg-white">

    <h3 class="text-xs font-semibold mb-5 tracking-wide">
        BUKU LAINNYA
    </h3>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-5">

        @forelse($bukus as $buku)
        <div class="bg-[#f4f4f4] p-2 rounded-lg hover:shadow-md hover:scale-105 transition duration-300">

            <!-- COVER -->
            <img src="{{ $buku->cover ? asset('storage/'.$buku->cover) : '/images/default.jpg' }}"
                 class="w-full h-44 object-cover rounded-md">

            <div class="mt-2">

                <!-- JUDUL -->
                <p class="text-[11px] font-semibold leading-tight">
                    {{ $buku->judul }}
                </p>

                <!-- PENULIS -->
                <p class="text-[10px] text-gray-500">
                    {{ $buku->penulis }}
                </p>

                <!-- STOK -->
                <p class="text-[10px] mt-1 {{ $buku->stok > 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ $buku->stok > 0 ? 'Tersedia' : 'Stok Habis' }}
                </p>

                <!-- BUTTON PINJAM -->
                @auth
                    <a href="{{ route('pinjam.create', $buku->id) }}"
                       class="block mt-3 w-full text-center bg-black text-white text-[10px] py-2 rounded hover:bg-gray-800">
                        PINJAM
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="block mt-3 w-full text-center bg-black text-white text-[10px] py-2 rounded hover:bg-gray-800">
                        PINJAM
                    </a>
                @endauth

            </div>

        </div>

        @empty
        <div class="col-span-5 text-center text-gray-500 py-10">
            📚 Belum ada data buku
        </div>
        @endforelse

    </div>

    <!-- PAGINATION -->
    <div class="flex justify-center mt-10 text-xs">
        {{ $bukus->links() }}
    </div>

</div>

@endsection
