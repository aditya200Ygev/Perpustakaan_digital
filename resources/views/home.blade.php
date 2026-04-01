@extends('layouts.app')

@section('content')

<!-- HERO -->
<div class="relative">
 <img src="{{ asset('image/home.png') }}" class="w-full h-screen object-cover">

    <div class="absolute inset-0 bg-black/50 flex items-center px-20">
        <div class="text-white max-w-xl">
            <h1 class="text-4xl font-extrabold leading-tight">
                SELAMAT DATANG <br>
                DI PERPUSTAKAAN <br>
                SEKOLAH
            </h1>
            <p class="text-sm mt-3 text-gray-200">
                Lorem ipsum dolor sit amet consectetur adipisicing elit
            </p>

            <button class="mt-5 bg-black hover:bg-gray-800 px-5 py-2 text-sm rounded">
                SELENGKAPNYA
            </button>
        </div>
    </div>
</div>

<!-- TENTANG -->
<div class="text-center py-12 bg-gray-100 h-72">
    <h2 class="font-bold text-xl tracking-wide">TENTANG</h2>
    <p class="text-sm mt-3 max-w-2xl mx-auto text-gray-600">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur adipisicing elit.
    </p>
</div>

<!-- SLIDER / KOLEKSI BUKU -->
<div class="relative py-16">

    <!-- BACKGROUND -->
    <div class="absolute inset-0">
        <img src="{{ asset('image/bg1.jpg') }}"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>

    <!-- CONTENT -->
    <div class="relative max-w-6xl mx-auto px-6">

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-8 justify-items-center">

            <!-- ITEM -->
            <div class="text-center group">
                <img src="{{ asset('image/hantu.jpg') }}"
                     class="w-36 h-52 object-cover rounded-lg shadow-lg
                            transform group-hover:scale-105 transition duration-300">
                <p class="text-sm mt-3 font-semibold text-white">
                    Hantu Rumah Kos
                </p>
            </div>

            <div class="text-center group">
                <img src="{{ asset('image/hantu.jpg') }}"
                     class="w-36 h-52 object-cover rounded-lg shadow-lg
                            transform group-hover:scale-105 transition duration-300">
                <p class="text-sm mt-3 font-semibold text-white">
                    Rahasia Lorong Jiwa
                </p>
            </div>

            <div class="text-center group">
                <img src="{{ asset('image/hantu.jpg') }}"
                     class="w-36 h-52 object-cover rounded-lg shadow-lg
                            transform group-hover:scale-105 transition duration-300">
                <p class="text-sm mt-3 font-semibold text-white">
                    Lukisan Senja
                </p>
            </div>

            <div class="text-center group">
                <img src="{{ asset('image/hantu.jpg') }}"
                     class="w-36 h-52 object-cover rounded-lg shadow-lg
                            transform group-hover:scale-105 transition duration-300">
                <p class="text-sm mt-3 font-semibold text-white">
                    Jejak Kehidupan
                </p>
            </div>

        </div>

    </div>
</div>
<!-- BUKU TERBARU -->
<div class="px-6 md:px-16 py-14 bg-gray-100 overflow-hidden">

    <div class="grid md:grid-cols-2 gap-10 items-stretch min-h-[420px]">

        <!-- KIRI -->
        <div class="bg-white shadow-xl rounded-xl overflow-hidden group flex flex-col">

            <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f"
                 class="w-full h-60 object-cover">

            <div class="p-5 flex flex-col flex-grow">
                <h4 class="font-semibold text-lg">Hantu di Rumah Kos</h4>

                <p class="text-sm text-gray-500 mt-2 flex-grow">
                    Kisah misteri yang terjadi di sebuah rumah kos tua...
                </p>

                <button class="mt-4 bg-black text-white text-xs px-5 py-2 rounded-full">
                    Lihat Detail Buku
                </button>
            </div>
        </div>

        <!-- KANAN -->
        <div class="grid grid-cols-2 gap-6 h-full auto-rows-fr">

            @for ($i = 1; $i <= 4; $i++)
            <div class="bg-white shadow-md rounded-xl overflow-hidden flex flex-col">

                <img src="https://source.unsplash.com/300x400/?book&sig={{ $i }}"
                     class="w-full h-28 object-cover">

                <div class="p-3 flex flex-col flex-grow">
                    <p class="text-sm font-medium flex-grow">
                        Judul Buku {{ $i }}
                    </p>

                    <button class="mt-2 bg-black text-white text-[11px] px-4 py-1.5 rounded-full">
                        Detail
                    </button>
                </div>

            </div>
            @endfor

        </div>

    </div>

</div>
<!-- GALERI -->
<div class="px-16 pb-14">
    <h3 class="text-center font-bold text-lg mb-8 tracking-wide">GALERI BUKU</h3>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @for ($i = 1; $i <= 8; $i++)
        <div class="bg-white shadow rounded overflow-hidden hover:shadow-xl transition">
            <img src="/images/buku{{$i}}.jpg" class="w-full h-48 object-cover">
            <div class="p-2 text-center">
                <p class="text-xs font-medium">Judul Buku {{$i}}</p>
                 <button class="mt-3 bg-black text-white text-xs px-4 py-2 rounded hover:bg-gray-800">
                    Lihat Detail Buku
                </button>
            </div>
        </div>
        @endfor
    </div>

    <div class="text-center mt-8">
        <button class="bg-black text-white px-6 py-2 text-sm rounded hover:bg-gray-800">
            LIHAT SEMUA BUKU
        </button>
    </div>
</div>

@endsection
