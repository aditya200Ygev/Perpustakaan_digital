@extends('dashboard.app')

@section('title', 'Edit Anggota')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow">

    <h1 class="text-lg font-bold mb-4">Edit Anggota</h1>

    <form action="{{ route('petugas.anggota.update', $anggota->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">

            <div>
                <label>Nama</label>
                <input type="text" name="name"
                       value="{{ $anggota->user->name }}"
                       class="w-full border p-2 rounded">
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email"
                       value="{{ $anggota->user->email }}"
                       class="w-full border p-2 rounded">
            </div>

            <div>
                <label>NIS</label>
                <input type="text" name="nis"
                       value="{{ $anggota->nis }}"
                       class="w-full border p-2 rounded">
            </div>

            <div>
                <label>Kelas</label>
                <input type="text" name="kelas"
                       value="{{ $anggota->kelas }}"
                       class="w-full border p-2 rounded">
            </div>

            <div>
                <label>Jurusan</label>
                <input type="text" name="jurusan"
                       value="{{ $anggota->jurusan }}"
                       class="w-full border p-2 rounded">
            </div>

            <div>
                <label>Foto</label>
                <input type="file" name="photo"
                       class="w-full border p-2 rounded">
            </div>

        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('petugas.anggota') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded">
               Kembali
            </a>

            <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded">
                Update
            </button>
        </div>

    </form>

</div>
@endsection
