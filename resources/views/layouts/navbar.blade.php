<div class="bg-white px-6 md:px-16 py-3 flex justify-between items-center shadow-md sticky top-0 z-50">


    <div class="font-bold text-sm bg-blue-600 text-white px-4 py-1.5 rounded-lg shadow">
        SMK N 3 BANJAR
    </div>


    <div class="hidden md:flex gap-8 text-sm font-medium">

        <a href="/" class="text-gray-700 hover:text-blue-600 transition duration-200">
            Home
        </a>

        <a href="/about" class="text-gray-700 hover:text-blue-600 transition duration-200">
            About
        </a>

        <a href="{{ route('buku.public') }}" class="text-gray-700 hover:text-blue-600 transition duration-200">
            Book
        </a>

        <a href="/contact" class="text-gray-700 hover:text-blue-600 transition duration-200">
            Contact
        </a>

    </div>

    <!-- RIGHT SIDE -->
    <div class="flex items-center gap-3">

        <!-- SEARCH -->
        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 text-sm rounded-lg transition">
            Search
        </button>

        @guest
            <!-- LOGIN -->
            <a href="{{ route('login') }}"
               class="border border-blue-600 text-blue-600 px-4 py-1.5 text-sm rounded-lg hover:bg-blue-600 hover:text-white transition duration-200">
               Login
            </a>
        @endguest

        @auth
            <!-- PROFILE -->
            <a href="
                @if(Auth::user()->role == 'anggota')
                    {{ route('dashboard.anggota') }}
                @elseif(Auth::user()->role == 'petugas')
                    {{ route('dashboard.petugas') }}
                @else
                    {{ route('dashboard.kepala') }}
                @endif
            "
            class="bg-blue-600 text-white px-4 py-1.5 text-sm rounded-lg hover:bg-blue-700 transition duration-200">
                Profile
            </a>

            <!-- LOGOUT -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-red-500 text-sm hover:text-red-600 transition">
                    Logout
                </button>
            </form>
        @endauth

    </div>

</div>
