@extends('layouts.app')

@section('title')
KONTAK KAMI
@endsection

@section('content')

<div class="bg-gray-100 py-16 px-6 md:px-16">

    <h2 class="text-center font-bold text-2xl mb-10 tracking-wide">
        KONTAK KAMI
    </h2>

    <div class="max-w-5xl mx-auto bg-white shadow-xl rounded-2xl p-8 md:p-10">

        <form class="grid md:grid-cols-2 gap-8">

            <div class="space-y-5">

                <div>
                    <label class="text-sm font-medium text-gray-700">Subject</label>
                    <input type="text"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg mt-1
                               focus:outline-none focus:ring-2 focus:ring-black">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Name</label>
                    <input type="text"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg mt-1
                               focus:outline-none focus:ring-2 focus:ring-black">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg mt-1
                               focus:outline-none focus:ring-2 focus:ring-black">
                </div>

            </div>


            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea rows="8"
                    class="w-full border border-gray-300 px-4 py-3 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-black resize-none"></textarea>
            </div>

        </form>


        <div class="mt-8 text-right">
            <button class="bg-black text-white px-8 py-3 rounded-lg
                           hover:bg-gray-800 transition">
                KIRIM PESAN
            </button>
        </div>

    </div>

</div>

@endsection
