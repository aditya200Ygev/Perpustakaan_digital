<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Digital Library</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Custom scrollbar untuk sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 10px; }
    </style>
</head>

<body class="bg-gray-100 flex overflow-hidden">

    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col h-screen fixed z-50 border-r border-slate-800 shadow-2xl">

        <div class="px-6 py-8">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 p-2 rounded-lg shadow-lg shadow-blue-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-xl font-black text-white tracking-tight">PETUGAS <span class="text-blue-500">System</span></span>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-1 sidebar-scroll overflow-y-auto">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-4 mb-4">Main Menu</p>

          @php
    $menus = [
        ['route' => 'dashboard.petugas', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 001 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard'],
        ['route' => 'petugas.anggota', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Data Anggota'],
        ['route' => 'petugas.buku', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Data Buku'],
        ['route' => 'petugas.peminjaman.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Peminjaman'],
        ['route' => 'petugas.denda.index', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Denda'],

        // --- TAMBAHKAN MENU LAPORAN DI BAWAH INI ---
        ['route' => 'petugas.laporan', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z', 'label' => 'Laporan'],
    ];
@endphp

            @foreach($menus as $menu)
                <a href="{{ route($menu['route']) }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group
                   {{ request()->routeIs($menu['route']) ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-bold' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs($menu['route']) ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}" />
                    </svg>
                    <span class="text-sm tracking-wide">{{ $menu['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="p-4 bg-slate-800/40 mt-auto border-t border-slate-800">
            <div class="flex items-center gap-3 p-2">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-blue-500/20 bg-blue-600 flex items-center justify-center shadow-lg">
                        @if(Auth::user()->photo)
                            <img src="{{ asset('storage/' . Auth::user()->photo) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</span>
                        @endif
                    </div>
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-slate-900 rounded-full"></div>
                </div>

                <div class="overflow-hidden">
                    <h4 class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</h4>
                    <p class="text-[10px] text-blue-400 uppercase font-black tracking-tighter">{{ Auth::user()->role }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 mt-4">
                <a href="{{ route('profile.petugas.edit') }}"
                   class="flex items-center justify-center py-2 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 hover:text-white transition-all text-[11px] font-bold">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                            class="flex items-center justify-center w-full py-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all text-[11px] font-bold">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

    </aside>

    <main class="ml-64 flex-1 h-screen overflow-y-auto">
        <header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 px-8 py-4 border-b border-gray-200 flex justify-between items-center shadow-sm">
            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Panel Petugas Perpustakaan</h2>
            <div class="text-xs text-gray-400 font-medium">{{ now()->translatedFormat('l, d F Y') }}</div>
        </header>

        <div class="p-8">
            @yield('content')
             @stack('scripts')
        </div>
    </main>

</body>
</html>
