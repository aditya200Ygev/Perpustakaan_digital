<nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 px-6 md:px-16 py-4 flex justify-between items-center sticky top-0 z-50">

    <div class="flex items-center gap-2">
        <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
        <span class="font-black text-slate-800 tracking-tighter text-lg uppercase">
            SMK N 3 <span class="text-blue-600">Banjar</span>
        </span>
    </div>

    <div class="hidden md:flex items-center gap-10">
        <a href="/" class="text-[13px] font-bold uppercase tracking-widest text-slate-500 hover:text-blue-600 transition-all">Home</a>
        <a href="/about" class="text-[13px] font-bold uppercase tracking-widest text-slate-500 hover:text-blue-600 transition-all">About</a>
        <a href="{{ route('buku.public') }}" class="text-[13px] font-bold uppercase tracking-widest text-slate-500 hover:text-blue-600 transition-all">Books</a>
        <a href="/contact" class="text-[13px] font-bold uppercase tracking-widest text-slate-500 hover:text-blue-600 transition-all">Contact</a>
    </div>

    <div class="flex items-center gap-4">
        <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>

        <div class="h-6 w-[1px] bg-gray-200 mx-1 hidden sm:block"></div>

        @guest
            <a href="{{ route('login') }}"
               class="text-[11px] font-black uppercase tracking-[0.2em] px-6 py-2.5 bg-slate-900 text-white rounded-xl hover:bg-blue-600 shadow-lg shadow-slate-200 transition-all active:scale-95">
                Login
            </a>
        @endguest

        @auth
            <div class="flex items-center gap-4">
                @php
                    $dashboardRoute = match(Auth::user()->role) {
                        'anggota' => route('dashboard.anggota'),
                        'petugas' => route('dashboard.petugas'),
                        default   => route('dashboard.kepala'),
                    };
                @endphp

                <a href="{{ $dashboardRoute }}" class="flex items-center gap-3 group">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-black text-slate-900 leading-none uppercase tracking-tighter">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-blue-600 font-bold uppercase tracking-widest mt-0.5">{{ Auth::user()->role }}</p>
                    </div>

                    {{-- BAGIAN FOTO PROFILE --}}
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

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="p-2 text-slate-300 hover:text-red-500 transition-colors" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        @endauth
    </div>
</nav>
