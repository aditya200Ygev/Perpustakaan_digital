<nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 px-4 sm:px-6 md:px-16 py-4 flex justify-between items-center sticky top-0 z-50">

    {{-- LOGO --}}
    <div class="flex items-center gap-2">
        <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
        <span class="font-black text-slate-800 tracking-tighter text-sm sm:text-lg uppercase">
            SMK N 3 <span class="text-blue-600">Banjar</span>
        </span>
    </div>

    {{-- DESKTOP MENU --}}
    <div class="hidden md:flex items-center gap-10">
        <a href="/" class="text-[13px] font-bold uppercase tracking-widest text-slate-500 hover:text-blue-600 transition-all">Home</a>
        <a href="/about" class="text-[13px] font-bold uppercase tracking-widest text-slate-500 hover:text-blue-600 transition-all">About</a>
        <a href="{{ route('buku.public') }}" class="text-[13px] font-bold uppercase tracking-widest text-slate-500 hover:text-blue-600 transition-all">Books</a>
        <a href="/contact" class="text-[13px] font-bold uppercase tracking-widest text-slate-500 hover:text-blue-600 transition-all">Contact</a>
    </div>

    {{-- RIGHT ACTIONS --}}
    <div class="flex items-center gap-2 sm:gap-4">
        {{-- Search Button --}}
        <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>

        {{-- Divider --}}
        <div class="h-6 w-[1px] bg-gray-200 mx-1 hidden sm:block"></div>

        {{-- Guest: Login Button --}}
        @guest
            <a href="{{ route('login') }}"
               class="hidden sm:inline-flex text-[11px] font-black uppercase tracking-[0.2em] px-6 py-2.5 bg-slate-900 text-white rounded-xl hover:bg-blue-600 shadow-lg shadow-slate-200 transition-all active:scale-95">
                Login
            </a>
        @endguest

        {{-- Auth: User Profile --}}
        @auth
            <div class="hidden sm:flex items-center gap-4">
                @php
                    $dashboardRoute = match(Auth::user()->role) {
                        'anggota' => route('dashboard.anggota'),
                        'petugas' => route('dashboard.petugas'),
                        default   => route('dashboard.kepala'),
                    };
                @endphp

                <a href="{{ $dashboardRoute }}" class="flex items-center gap-3 group">
                    <div class="text-right hidden lg:block">
                        <p class="text-[10px] font-black text-slate-900 leading-none uppercase tracking-tighter">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-blue-600 font-bold uppercase tracking-widest mt-0.5">{{ Auth::user()->role }}</p>
                    </div>

                    {{-- Profile Photo --}}
                    <div class="w-10 h-10 rounded-xl overflow-hidden border-2 border-white shadow-sm ring-2 ring-blue-50 group-hover:ring-blue-600 transition-all">
                        @if(Auth::user()->photo)
                            <img src="{{ Storage::url(Auth::user()->photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-blue-100 flex items-center justify-center text-blue-600 font-black text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </a>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="p-2 text-slate-300 hover:text-red-500 transition-colors" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        @endguest

        {{-- MOBILE MENU BUTTON --}}
        <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" aria-label="Toggle menu">
            <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</nav>

{{-- MOBILE MENU PANEL --}}
<div id="mobile-menu" class="md:hidden hidden bg-white border-b border-gray-100 shadow-lg">
    <div class="px-4 py-4 space-y-3">

        {{-- Navigation Links --}}
        <a href="/" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">
            <span class="text-lg"></span> Home
        </a>
        <a href="/about" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">
            <span class="text-lg"></span> About
        </a>
        <a href="{{ route('buku.public') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">
            <span class="text-lg"></span> Books
        </a>
        <a href="/contact" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">
            <span class="text-lg"></span> Contact
        </a>

        {{-- Divider --}}
        <div class="border-t border-gray-100 my-3"></div>

        @guest
            {{-- Guest: Login Button --}}
            <a href="{{ route('login') }}"
               class="block w-full text-center px-4 py-3 text-sm font-black uppercase tracking-widest bg-slate-900 text-white rounded-xl hover:bg-blue-600 shadow-lg transition-all">
                🔐 Login
            </a>
            <a href="{{ route('register') }}"
               class="block w-full text-center px-4 py-3 text-sm font-bold text-slate-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
                ✨ Daftar Akun
            </a>
        @else
            {{-- Auth: User Info --}}
            <div class="flex items-center gap-3 px-4 py-3 bg-blue-50 rounded-xl">
                <div class="w-12 h-12 rounded-lg overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
                    @if(Auth::user()->photo)
                        <img src="{{ Storage::url(Auth::user()->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-blue-200 flex items-center justify-center text-blue-700 font-black">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-blue-600 font-bold uppercase tracking-wider">{{ Auth::user()->role }}</p>
                </div>
            </div>

            {{-- Dashboard Link --}}
            @php
                $dashboardRoute = match(Auth::user()->role) {
                    'anggota' => route('dashboard.anggota'),
                    'petugas' => route('dashboard.petugas'),
                    default   => route('dashboard.kepala'),
                };
            @endphp
            <a href="{{ $dashboardRoute }}" class="block px-4 py-3 text-sm font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">
                profile
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition-all">
                    <span class="text-lg"></span> Logout
                </button>
            </form>
        @endauth
    </div>
</div>

{{-- SCRIPT: Mobile Menu Toggle --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');

    // Toggle menu
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        menu.classList.toggle('hidden');
        menuIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!btn.contains(e.target) && !menu.contains(e.target) && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    });

    // Close menu when clicking on a link
    menu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function() {
            menu.classList.add('hidden');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        });
    });
});
</script>
