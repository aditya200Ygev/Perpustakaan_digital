@extends('layouts.app')

@section('title')
KONTAK KAMI
@endsection

@section('content')

<div class="bg-gray-100 py-16 px-6 md:px-16">
    <h2 class="text-center font-bold text-2xl mb-10 tracking-wide">KONTAK KAMI</h2>

    <div class="max-w-5xl mx-auto bg-white shadow-xl rounded-2xl p-8 md:p-10">

        {{-- Notifikasi Success --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('contact.store') }}" method="POST" class="grid md:grid-cols-2 gap-8">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="text-sm font-medium text-gray-700">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg mt-1
                               focus:outline-none focus:ring-2 focus:ring-black @error('subject') border-red-500 @enderror">
                    @error('subject') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg mt-1
                               focus:outline-none focus:ring-2 focus:ring-black @error('name') border-red-500 @enderror">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg mt-1
                               focus:outline-none focus:ring-2 focus:ring-black @error('email') border-red-500 @enderror">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea name="message" rows="8"
                    class="w-full border border-gray-300 px-4 py-3 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-black resize-none @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                @error('message') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 text-right">
                <button type="submit" class="bg-black text-white px-8 py-3 rounded-lg hover:bg-gray-800 transition">
                    KIRIM PESAN
                </button>
            </div>
        </form>

    </div>
</div>

@endsection
