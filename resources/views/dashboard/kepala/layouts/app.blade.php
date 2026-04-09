<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Kepala Perpustakaan</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }

        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-gray-100 flex overflow-hidden">

{{-- ================= SIDEBAR ================= --}}
<aside class="w-64 bg-slate-900 text-slate-300 flex flex-col h-screen fixed z-50 border-r border-slate-800 shadow-2xl">

    {{-- LOGO --}}
    <div class="px-6 py-8">
        <div class="flex items-center gap-3">
            <div class="bg-blue-600 p-2 rounded-lg shadow-lg shadow-blue-500/20">
                📚
            </div>
            <span class="text-xl font-black text-white tracking-tight">
                E-Lib <span class="text-blue-500">Kepala</span>
            </span>
        </div>
    </div>

    {{-- MENU --}}
    <nav class="flex-1 px-4 space-y-1 sidebar-scroll overflow-y-auto">

        @php
        $menus = [
            ['route' => 'dashboard.kepala', 'label' => 'Dashboard', 'icon' => '🏠'],
            ['route' => 'kepala.buku.index', 'label' => 'Monitoring Buku', 'icon' => '📚'],
            ['route' => 'kepala.sdm', 'label' => 'Data SDM', 'icon' => '👥'],
            ['route' => 'kepala.laporan', 'label' => 'Laporan Umum', 'icon' => '📊'],
        ];
        @endphp

        @foreach($menus as $menu)
            <a href="{{ route($menu['route']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
               {{ request()->routeIs($menu['route']) ? 'bg-blue-600 text-white shadow-lg font-bold' : 'hover:bg-slate-800 hover:text-white' }}">

                <span>{{ $menu['icon'] }}</span>
                <span class="text-sm tracking-wide">{{ $menu['label'] }}</span>
            </a>
        @endforeach

    </nav>

    {{-- USER --}}
    <div class="p-4 bg-slate-800/40 mt-auto border-t border-slate-800">

        <div class="flex items-center gap-3 p-2">
            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
            </div>

            <div>
                <h4 class="text-sm font-bold text-white">{{ Auth::user()->name }}</h4>
                <p class="text-[10px] text-blue-400 uppercase font-black">
                    Kepala Perpus
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mt-4">

            <a href="#"
               class="flex items-center justify-center py-2 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 hover:text-white text-[11px] font-bold">
                Profile
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full py-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white text-[11px] font-bold">
                    Keluar
                </button>
            </form>

        </div>
    </div>

</aside>

{{-- ================= CONTENT ================= --}}
<main class="ml-64 flex-1 h-screen overflow-y-auto">

    {{-- HEADER --}}
    <header class="bg-white sticky top-0 z-40 px-8 py-4 border-b flex justify-between items-center shadow-sm">
        <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest">
            Panel Kepala Perpustakaan
        </h2>

        <div class="text-xs text-gray-400">
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </header>

    {{-- CONTENT --}}
    <div class="p-8">
        @yield('content')
    </div>

</main>

</body>
</html>
