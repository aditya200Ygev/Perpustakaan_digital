@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="bg-gray-100 min-h-screen p-6">

  <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">

    <h2 class="text-xl font-semibold mb-6">Edit Profile</h2>

    @if(session('success'))
      <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf

      <!-- FOTO -->
      <div>
        <label class="text-sm">Foto Profile</label>
        <input type="file" name="photo" class="w-full border rounded px-3 py-2 mt-1">

        @if($user->photo)
          <img src="{{ asset('storage/'.$user->photo) }}" class="w-20 h-20 mt-2 rounded-full object-cover">
        @endif
      </div>

      <!-- Nama -->
      <div>
        <label class="text-sm">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}"
          class="w-full border rounded px-3 py-2 mt-1">
      </div>

      <!-- Email -->
      <div>
        <label class="text-sm">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}"
          class="w-full border rounded px-3 py-2 mt-1">
      </div>

      <!-- No Telp -->
      <div>
        <label class="text-sm">No Telp</label>
        <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}"
          class="w-full border rounded px-3 py-2 mt-1">
      </div>

      <!-- NIS -->
      <div>
        <label class="text-sm">NIS</label>
        <input type="text" name="nis" value="{{ old('nis', $user->anggota->nis ?? '') }}"
          class="w-full border rounded px-3 py-2 mt-1">
      </div>

      <!-- KELAS -->
      <div>
        <label class="text-sm">Kelas</label>

        <select name="kelas" class="w-full border rounded px-3 py-2 mt-1">
          <option value="">-- Pilih Kelas --</option>

          <option value="X" {{ old('kelas', $user->anggota->kelas ?? '') == 'X' ? 'selected' : '' }}>X</option>
          <option value="XI" {{ old('kelas', $user->anggota->kelas ?? '') == 'XI' ? 'selected' : '' }}>XI</option>
          <option value="XII" {{ old('kelas', $user->anggota->kelas ?? '') == 'XII' ? 'selected' : '' }}>XII</option>
        </select>

        @error('kelas')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex justify-end gap-2">
        <a href="/" class="px-4 py-2 bg-gray-300 rounded">Batal</a>

        <button type="submit"
          class="bg-green-800 text-white px-4 py-2 rounded hover:bg-green-900">
          Simpan
        </button>
      </div>

    </form>
  </div>

</div>
@endsection
