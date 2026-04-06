@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')

<div class="p-6 bg-gray-100 min-h-screen">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow">

        <div class="grid md:grid-cols-3 gap-6">

            {{-- COVER --}}
            <div class="flex justify-center">
                <img src="{{ $buku->cover ? Storage::url($buku->cover) : '/img/book.png' }}"
                     class="w-48 h-64 object-cover rounded-lg shadow">
            </div>

            {{-- DETAIL --}}
            <div class="md:col-span-2">

                <h2 class="text-2xl font-bold mb-4">{{ $buku->judul }}</h2>

                <div class="space-y-2 text-sm">
                    <p><b>Penulis:</b> {{ $buku->penulis }}</p>
                    <p><b>Penerbit:</b> {{ $buku->penerbit }}</p>
                    <p><b>Tahun:</b> {{ $buku->tahun_terbit }}</p>

                    <p>
                        <b>Stok:</b>
                        @if($buku->stok > 0)
                            <span class="text-green-600 font-semibold">{{ $buku->stok }}</span>
                        @else
                            <span class="text-red-600 font-semibold">Habis</span>
                        @endif
                    </p>
                </div>

                <p class="mt-4 text-sm text-gray-700">
                    <b>Deskripsi:</b><br>
                    {{ $buku->deskripsi }}
                </p>

                {{-- SUCCESS --}}
                @if(session('success'))
                    <div class="mt-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- ERROR (DITOLAK / GAGAL) --}}
                @if(session('error'))
                    <div class="mt-4 p-3 bg-red-100 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- FORM PINJAM --}}
                <form id="pinjamForm" action="{{ route('pinjam.store') }}" method="POST" class="mt-6">
                    @csrf

                    <input type="hidden" name="buku_id" value="{{ $buku->id }}">

                    {{-- JUMLAH --}}
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold text-sm">Jumlah Pinjam</label>

                        <select name="jumlah"
                                class="w-full border rounded-lg px-3 py-2"
                                required>

                            @for($i = 1; $i <= min(36, $buku->stok); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor

                        </select>
                    </div>

                    {{-- BUTTON --}}
                    <button type="button"
                        onclick="confirmPinjam()"
                        class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 transition"
                        @if($buku->stok < 1) disabled @endif>

                        🟢 PINJAM
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

{{-- POPUP --}}
<script>
function confirmPinjam() {
    if (confirm('Yakin ingin meminjam buku ini?')) {
        document.getElementById('pinjamForm').submit();
    }
}
</script>

@endsection
