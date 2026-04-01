<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>



    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-white">

    {{-- NAVBAR --}}
    @include('layouts.navbar')

    {{-- HERO GLOBAL --}}
    @hasSection('title')
    <div class="relative">
    <img src="@yield('hero_image', asset('image/hero.jpg'))"
     class="w-full h-[420px] object-cover bg-black/">

        <div class="absolute inset-0 bg-black/70 flex items-center px-20">
            <div class="text-white">
                <h1 class="text-4xl font-bold leading-tight tracking-wide">
                    @yield('title')
                </h1>
            </div>
        </div>
    </div>
    @endif

    {{-- CONTENT --}}
    @yield('content')

    {{-- FOOTER --}}
    @include('layouts.footer')

</body>
</html>
