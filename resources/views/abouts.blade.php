@extends('layouts.app')

@section('title')
TENTANG <br> PERPUSTAKAAN DIGITAL
@endsection

@section('content')

<div class="px-6 md:px-16 py-20 bg-white">
    <div class="grid md:grid-cols-2 gap-12 items-center">
        <div class="space-y-5">
            <div class="w-20 h-1.5 bg-blue-600 rounded-full"></div> {{-- Aksen dekoratif --}}
            <h2 class="font-bold text-3xl tracking-tighter text-gray-900 uppercase">
                Perpustakaan <br> <span class="text-gray-400">Digital Modern</span>
            </h2>

            <p class="text-sm text-gray-500 leading-relaxed text-justify">
                Kami hadir sebagai solusi literasi di era digital. Perpustakaan digital hadir untuk memudahkan akses informasi dan literasi kapan saja dan di mana saja tanpa batasan fisik ruang dan waktu.
            </p>

            <p class="text-sm text-gray-500 leading-relaxed text-justify">
                Dengan dukungan sistem teknologi terbaru, pengguna dapat dengan mudah menjelajahi koleksi, membaca secara daring, hingga mengelola peminjaman buku dalam satu platform yang terintegrasi.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4 relative">
            <img src="/image/bg1.jpg" class="rounded-2xl shadow-xl w-full h-52 object-cover transform translate-y-4 hover:scale-105 transition duration-500">
            <img src="/image/bg1.jpg" class="rounded-2xl shadow-xl w-full h-52 object-cover transform -translate-y-4 hover:scale-105 transition duration-500">
        </div>
    </div>
</div>

<div class="px-6 md:px-16 py-20 bg-[#f9f9f9]">
    <div class="grid md:grid-cols-2 gap-16">

        <div class="flex flex-col space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <img src="/image/bg1.jpg" class="rounded-2xl h-40 w-full object-cover shadow-md hover:rotate-2 transition">
                <img src="/image/bg1.jpg" class="rounded-2xl h-40 w-full object-cover shadow-md hover:-rotate-2 transition">
            </div>
            <div>
                <h3 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                    <span class="w-8 h-[2px] bg-blue-500"></span> MISI
                </h3>
                <p class="text-sm text-gray-500 mt-4 leading-relaxed">
                    Menyediakan akses informasi yang luas, meningkatkan minat baca melalui koleksi yang relevan, serta menghadirkan layanan perpustakaan berbasis digital yang inklusif dan mudah diakses oleh semua kalangan.
                </p>
            </div>
        </div>

        <div class="flex flex-col-reverse md:flex-col justify-between">
            <div class="mb-6 md:mb-0">
                <h3 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                    <span class="w-8 h-[2px] bg-blue-500"></span> VISI
                </h3>
                <p class="text-sm text-gray-500 mt-4 leading-relaxed">
                    Menjadi pusat literasi digital yang inovatif, modern, dan terpercaya dalam membangun masyarakat yang cerdas, berwawasan luas, dan literat melalui teknologi informasi.
                </p>
            </div>

            <div class="relative mt-6">
                <img src="/image/buka.jpg" class="rounded-2xl shadow-2xl w-full h-48 object-cover border-4 border-white">
                {{-- Ornamen kecil untuk mempercantik --}}
                <div class="absolute -bottom-4 -right-4 w-20 h-20 bg-yellow-400 -z-10 rounded-2xl"></div>
            </div>
        </div>

    </div>
</div>

@endsection
