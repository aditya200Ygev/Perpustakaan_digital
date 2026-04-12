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

        /* Transition untuk sidebar */
        .sidebar-transition { transition: transform 0.3s ease-in-out; }

        /* Overlay untuk mobile */
        .sidebar-overlay {
            transition: opacity 0.3s ease-in-out;
            background: rgba(0,0,0,0.5);
        }

        /* Prevent body scroll when mobile menu open */
        body.menu-open { overflow: hidden; }
    </style>
</head>

<body class="bg-gray-100 flex overflow-hidden">

    {{-- MOBILE OVERLAY --}}
    <div id="sidebar-overlay"
         class="sidebar-overlay fixed inset-0 z-40 bg-black/50 opacity-0 invisible md:hidden"
         onclick="toggleSidebar()"></div>

    {{-- SIDEBAR --}}
    <aside id="sidebar"
           class="sidebar-transition w-64 bg-slate-900 text-slate-300 flex flex-col h-screen fixed z-50 border-r border-slate-800 shadow-2xl md:translate-x-0 -translate-x-full">

        {{-- Sidebar Header --}}
        <div class="px-6 py-6 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 p-2 rounded-lg shadow-lg shadow-blue-500/20 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <span class="text-lg font-black text-white tracking-tight block leading-tight">PETUGAS</span>
                    <span class="text-xs font-bold text-blue-500 uppercase tracking-wider">Perpustakaan</span>
                </div>
            </div>

            {{-- Close Button (Mobile Only) --}}
            <button onclick="toggleSidebar()" class="md:hidden absolute top-4 right-4 p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-1 sidebar-scroll overflow-y-auto">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-3">Menu Utama</p>

            @php
                $menus = [
                    ['route' => 'dashboard.petugas', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 001 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard'],
                    ['route' => 'petugas.anggota', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Data Anggota'],
                    ['route' => 'petugas.buku', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Data Buku'],
                    ['route' => 'petugas.peminjaman.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Peminjaman'],
                    ['route' => 'petugas.denda.index', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Denda'],
                    ['route' => 'petugas.contact.index', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Kontak'],
                    ['route' => 'petugas.laporan', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z', 'label' => 'Laporan'],
                ];
            @endphp

            @foreach($menus as $menu)
                <a href="{{ route($menu['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                   {{ request()->routeIs($menu['route']) ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-bold' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 {{ request()->routeIs($menu['route']) ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}" />
                    </svg>
                    <span class="text-sm font-medium tracking-wide truncate">{{ $menu['label'] }}</span>
                </a>
            @endforeach
        </nav>

        {{-- User Profile Section --}}
        <div class="p-4 bg-slate-800/50 border-t border-slate-800">
            <div class="flex items-center gap-3 p-2 rounded-lg bg-slate-800/50">
                <div class="relative flex-shrink-0">
                    <div class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-blue-500/30 bg-blue-600 flex items-center justify-center shadow-lg">
                        @if(Auth::user()->photo)
                            <img src="{{ asset('storage/' . Auth::user()->photo) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</span>
                        @endif
                    </div>
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-slate-900 rounded-full"></div>
                </div>

                <div class="overflow-hidden min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</h4>
                    <p class="text-[10px] text-blue-400 uppercase font-bold tracking-tight">{{ Auth::user()->role }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 mt-3">
                <a href="{{ route('profile.petugas.edit') }}"
                   class="flex items-center justify-center gap-2 py-2 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 hover:text-white transition-all text-xs font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                            class="flex items-center justify-center gap-2 w-full py-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-all text-xs font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 h-screen overflow-y-auto md:ml-64 transition-all duration-300">

        {{-- Mobile Header with Hamburger --}}
        <header class="bg-white/80 backdrop-blur-md sticky top-0 z-30 px-4 sm:px-6 py-4 border-b border-gray-200 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-3">
                {{-- Hamburger Button (Mobile Only) --}}
                <button onclick="toggleSidebar()" class="md:hidden p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" aria-label="Toggle sidebar">
                    <svg id="hamburger-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h2 class="text-base font-bold text-gray-800">Panel Petugas</h2>
                    <p class="text-xs text-gray-500 hidden sm:block">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <div class="p-4 sm:p-6 lg:p-8">
            @yield('content')
            @stack('scripts')
        </div>
    </main>

    {{-- SCRIPT: Sidebar Toggle --}}
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const body = document.body;

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('opacity-0');
            overlay.classList.toggle('invisible');
            body.classList.toggle('menu-open');
        }

        // Close sidebar when clicking a link (mobile)
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    toggleSidebar();
                }
            });
        });

        // Close sidebar on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                const body = document.body;

                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('opacity-0', 'invisible');
                body.classList.remove('menu-open');
            }
        });
    </script>

</body>
</html>
