<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | E-Lib Premium</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .sidebar-scroll::-webkit-scrollbar { width: 3px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .nav-active {
            background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        /* Animasi halus untuk hover */
        .nav-item { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>

<body class="bg-slate-50 flex overflow-hidden text-slate-900">

{{-- ================= SIDEBAR ================= --}}
<aside class="w-72 bg-[#0f172a] text-slate-400 flex flex-col h-screen fixed z-50 border-r border-slate-800/50">

    {{-- LOGO SECTION --}}
    <div class="px-8 py-10">
        <div class="flex items-center gap-3">
            <div class="relative">
                <div class="absolute -inset-1 bg-blue-500 rounded-xl blur opacity-25"></div>
                <div class="relative bg-blue-600 p-2.5 rounded-xl shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-extrabold text-white tracking-tight leading-none">E-LIBRARY</span>
                <span class="text-[10px] text-blue-400 font-bold tracking-[0.2em] mt-1 uppercase">Management System</span>
            </div>
        </div>
    </div>

    {{-- NAV MENU --}}
    <nav class="flex-1 px-6 space-y-2 sidebar-scroll overflow-y-auto">
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4 mb-4">Main Menu</p>

        @php
        $menus = [
            ['route' => 'dashboard.kepala', 'label' => 'Dashboard', 'icon' => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />'],
            ['route' => 'kepala.buku.index', 'label' => 'Monitoring Buku', 'icon' => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />'],
            ['route' => 'kepala.sdm', 'label' => 'Data SDM', 'icon' => '<path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />'],
            ['route' => 'kepala.laporan', 'label' => 'Laporan Umum', 'icon' => '<path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />'],
        ];
        @endphp

        @foreach($menus as $menu)
            <a href="{{ route($menu['route']) }}"
               class="nav-item group flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium
               {{ request()->routeIs($menu['route']) ? 'nav-active text-white' : 'hover:bg-slate-800/50 hover:text-white' }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5 {{ request()->routeIs($menu['route']) ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    {!! $menu['icon'] !!}
                </svg>

                <span class="tracking-wide">{{ $menu['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- USER FOOTER --}}
    <div class="p-6">
        <div class="bg-slate-800/50 rounded-2xl p-4 border border-slate-700/50 shadow-inner">
            <div class="flex items-center gap-3 mb-4">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-lg ring-2 ring-slate-700">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-slate-800 rounded-full"></div>
                </div>

                <div class="overflow-hidden">
                    <h4 class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</h4>
                    <span class="text-[10px] text-blue-400 font-bold uppercase tracking-tighter">Kepala Perpustakaan</span>
                </div>
            </div>

            <div class="flex gap-2">
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white text-[10px] font-bold transition-all">
                        LOGOUT
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

{{-- ================= CONTENT ================= --}}
<main class="ml-72 flex-1 h-screen overflow-y-auto">

    {{-- MODERN HEADER --}}
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 px-10 py-5 border-b border-slate-200 flex justify-between items-center">
        <div>
            <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Sistem Informasi</h2>
            <p class="text-lg font-bold text-slate-800">Dashboard Kontrol</p>
        </div>

        <div class="flex items-center gap-6">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-800">{{ now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-[10px] text-slate-400 uppercase tracking-tighter">Waktu Operasional</p>
            </div>

            {{-- Notification Bell --}}
            <button class="relative p-2 text-slate-400 hover:text-blue-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
            </button>
        </div>
    </header>

    {{-- MAIN CONTENT AREA --}}
    <div class="p-10 max-w-[1400px] mx-auto">
        <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
            @yield('content')
        </div>
    </div>

</main>

</body>
</html>
