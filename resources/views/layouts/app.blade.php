<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') — Perpustakaan Digital</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Memastikan footer tetap di bawah jika konten sedikit */
        .main-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content-grow {
            flex-grow: 1;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900 selection:bg-blue-100 selection:text-blue-700">

    <div class="main-wrapper">

        {{-- NAVBAR --}}
        <header class="sticky top-0 z-50">
            @include('layouts.navbar')
        </header>

        <main class="content-grow">

            {{-- HERO SECTION --}}
            @hasSection('title')
            <section class="relative h-[400px] md:h-[450px] w-full overflow-hidden">
                <img src="@yield('hero_image', asset('image/hero.jpg'))"
                     alt="Hero Background"
                     class="absolute inset-0 w-full h-full object-cover">

                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/60 to-transparent"></div>

                <div class="relative h-full max-w-7xl mx-auto px-6 md:px-12 lg:px-20 flex items-center">
                    <div class="max-w-2xl">
                        <nav class="flex mb-4 items-center space-x-2 text-blue-400 text-xs font-bold uppercase tracking-widest">
                            <span>Perpustakaan</span>
                            <span class="text-slate-500">/</span>
                            <span class="text-slate-200">@yield('title')</span>
                        </nav>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] tracking-tight">
                            @yield('title')
                        </h1>
                        <div class="mt-4 h-1.5 w-20 bg-blue-600 rounded-full"></div>
                    </div>
                </div>
            </section>
            @endif

            {{-- MAIN CONTENT AREA --}}
            <div class="py-10">
                @yield('content')
                
            </div>

        </main>

        {{-- FOOTER --}}
        @include('layouts.footer')

    </div>

    {{-- SCRIPTS --}}
    @stack('scripts')

</body>
</html>
