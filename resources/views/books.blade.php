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
                Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum
            </p>

            <button class="mt-4 bg-black text-white text-xs px-5 py-2 rounded-lg hover:bg-gray-800">
                Baca Selengkapnya
            </button>
        </div>

    </div>
</div>

<!-- KATEGORI -->
<div class="bg-white py-6">
    <div class="flex justify-center gap-3 text-xs font-medium">

        <button class="px-5 py-2 bg-gray-200 rounded-full">PENDIDIKAN</button>

        <button class="px-5 py-2 bg-yellow-400 text-black rounded-full shadow">
            NOVEL
        </button>

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

        @for ($i = 1; $i <= 15; $i++)
        <div class="bg-[#f4f4f4] p-2 rounded-lg hover:shadow-md hover:scale-105 transition duration-300">

            <img src="/images/buku{{ ($i % 5) + 1 }}.jpg"
                 class="w-full h-44 object-cover rounded-md">

            <div class="mt-2">
                <p class="text-[11px] font-semibold leading-tight">
                    Judul Buku {{$i}}
                </p>
                <p class="text-[10px] text-gray-500">
                    Penulis Buku
                </p>

                <button class="mt-3 w-full bg-black text-white text-[10px] py-2 rounded hover:bg-gray-800">
                    SELENGKAPNYA
                </button>
            </div>

        </div>
        @endfor

    </div>

    <!-- PAGINATION -->
    <div class="flex justify-center mt-10 gap-2 text-xs">

        <button class="w-7 h-7 bg-gray-300 rounded">1</button>
        <button class="w-7 h-7 bg-black text-white rounded">2</button>
        <button class="w-7 h-7 bg-gray-300 rounded">3</button>

    </div>

</div>

@endsection
