<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-gray-800 text-white flex flex-col justify-between min-h-screen">

        <!-- LOGO -->
        <div>
            <div class="p-5 text-lg font-bold border-b border-gray-700">
                📚 Perpustakaan
            </div>

            <!-- MENU -->
            <nav class="p-4 space-y-2 text-sm">

                <!-- DASHBOARD -->
                <a href="{{ route('dashboard.petugas') }}"
                   class="block px-4 py-2 rounded transition-all duration-200
                   {{ request()->routeIs('dashboard.petugas.index') ? 'bg-gray-300 text-black' : 'hover:bg-gray-700' }}">
                   🏠 Dashboard
                </a>

                <!-- DATA ANGGOTA -->
                <a href="{{ route('petugas.anggota') }}"
                   class="block px-4 py-2 rounded transition-all duration-200
                   {{ request()->routeIs('petugas.anggota*') ? 'bg-gray-300 text-black' : 'hover:bg-gray-700' }}">
                   👥 Data Anggota
                </a>

                <!-- DATA BUKU -->
                <a href="{{ route('petugas.buku') }}"
                   class="block px-4 py-2 rounded transition-all duration-200
                   {{ request()->routeIs('petugas.buku*') ? 'bg-gray-300 text-black' : 'hover:bg-gray-700' }}">
                   📚 Data Buku
                </a>

                <!-- PEMINJAMAN -->
                <a href="{{ route('petugas.peminjaman.index') }}"
                   class="block px-4 py-2 rounded transition-all duration-200 hover:bg-gray-700">
                   📖 Peminjaman
                </a>

                <a href="#"
                   class="block px-4 py-2 rounded transition-all duration-200 hover:bg-gray-700">
                   📖 pengembalian
                </a>

                <!-- LAPORAN -->
                <a href="#"
                   class="block px-4 py-2 rounded transition-all duration-200 hover:bg-gray-700">
                   📊 Laporan
                </a>

            </nav>
        </div>

        <!-- USER -->
        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center gap-3">

                <!-- FOTO -->
                <div class="w-10 h-10 rounded-full overflow-hidden bg-green-700 flex items-center justify-center">
                    @if(Auth::user()->photo)
                        <img src="{{ asset('storage/'.Auth::user()->photo) }}"
                             class="w-full h-full object-cover">
                    @else
                        <span class="text-white font-bold">
                            {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                        </span>
                    @endif
                </div>

                <!-- INFO -->
                <div>
                    <div class="text-sm font-semibold">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-xs text-gray-400">
                        {{ Auth::user()->role }}
                    </div>
                </div>
            </div>

            <!-- LOGOUT -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="mt-3 w-full bg-red-600 text-sm py-2 rounded hover:bg-red-700 transition">
                    🚪 Keluar
                </button>
            </form>
        </div>

    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6 bg-gray-100 min-h-screen">
        @yield('content')
    </main>

</body>
</html>
