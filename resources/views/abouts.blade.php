@extends('layouts.app')

@section('title')
TENTANG <br> PERPUSTAKAAN DIGITAL
@endsection

@section('content')

<!-- TENTANG -->
<div class="px-6 md:px-16 py-14 bg-white">
    <div class="grid md:grid-cols-2 gap-10 items-center">

        <!-- TEXT -->
        <div>
            <h2 class="font-bold text-xl mb-4 tracking-wide">
                PERPUSTAKAAN DIGITAL
            </h2>

            <p class="text-sm text-gray-600 leading-relaxed">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                Perpustakaan digital hadir untuk memudahkan akses informasi
                dan literasi kapan saja dan di mana saja.
            </p>

            <p class="text-sm text-gray-600 mt-3 leading-relaxed">
                Dengan sistem modern, pengguna dapat membaca, meminjam,
                dan mengelola buku secara online dengan mudah dan cepat.
            </p>
        </div>

        <!-- IMAGE -->
        <div class="grid grid-cols-2 gap-4">
            <img src="/image/bg1.jpg" class="rounded-xl shadow-md w-full h-40 object-cover">
            <img src="/image/bg1.jpg" class="rounded-xl shadow-md w-full h-40 object-cover">
        </div>

    </div>
</div>

<!-- VISI MISI -->
<div class="px-6 md:px-16 py-14 bg-gray-100">
    <div class="grid md:grid-cols-2 gap-10 items-start">

        <!-- MISI -->
        <div class="bg-gray-100 p-6 rounded-xl ">
            <div class="grid grid-cols-2 gap-3 mb-4">
                <img src="/image/bg1.jpg" class="rounded-lg h-32 w-full object-cover">
                <img src="/image/bg1.jpg" class="rounded-lg h-32 w-full object-cover">
            </div>

            <h3 class="font-semibold text-lg">MISI</h3>
            <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                Menyediakan akses informasi yang luas, meningkatkan minat baca,
                serta menghadirkan layanan perpustakaan berbasis digital yang
                mudah diakses oleh semua kalangan.
            </p>
        </div>

        <!-- VISI -->
        <div class="bg-gray-100 p-6 rounded-xl flex flex-col">
            <h3 class="font-semibold text-lg mb-2">VISI</h3>

            <p class="text-sm text-gray-600 leading-relaxed">
                Menjadi perpustakaan digital yang modern, inovatif, dan
                terpercaya dalam menyediakan sumber informasi dan literasi
                bagi masyarakat luas.
            </p>

            <img src="/image/buka.jpg" class="rounded-xl shadow mt-4 w-full h-40 object-cover">
        </div>

    </div>
</div>

@endsection
