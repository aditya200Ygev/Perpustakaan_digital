@extends('dashboard.app')

@section('title', 'Ganti Password')

@section('content')
<div class="bg-gray-50 min-h-screen p-4 md:p-8">
    <div class="max-w-xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">
                Ganti <span class="text-blue-600">Password</span>
            </h1>
            <p class="text-gray-500 text-sm mt-1">Perbarui kata sandi akun Anda.</p>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Form Password --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- ✅ ROUTE DINAMIS: Sesuaikan dengan role user --}}
            <form method="POST" action="{{ route(Auth::user()->role . '.password.update') }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Password Lama --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Password Saat Ini</label>
                    <input type="password" name="current_password"
                        class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none border text-sm font-bold">
                    @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Password Baru --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Password Baru</label>
                    <input type="password" name="password"
                        class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none border text-sm font-bold">
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    <p class="text-[10px] text-gray-400 mt-1 ml-1">Minimal 8 karakter, mengandung huruf dan angka.</p>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none border text-sm font-bold">
                </div>

                {{-- Tombol Submit --}}
                <div class="flex justify-end gap-3 pt-4">
                    {{-- ✅ BACK LINK DINAMIS --}}
                    <a href="{{ route(Auth::user()->role . '.profile.edit') }}"
                       class="px-6 py-2.5 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-100 transition-all">
                        ← Kembali ke Profile
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all shadow-md shadow-blue-100">
                        🔐 Update Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Link Bantuan --}}
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-400">
                Lupa password?
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline font-bold">
                    Reset via email
                </a>
            </p>
        </div>

    </div>
</div>
@endsection
