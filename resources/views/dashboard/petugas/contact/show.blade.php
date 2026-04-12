@extends('dashboard.app')
@section('title', 'Detail Pesan')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <a href="{{ route('petugas.contact.index') }}" class="text-gray-600 hover:text-black mb-4 inline-block">
        ← Kembali
    </a>

    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-xl font-bold">{{ $contact->subject }}</h1>
            <span class="px-3 py-1 text-sm rounded-full {{ $contact->is_read ? 'bg-gray-100' : 'bg-blue-100 text-blue-700' }}">
                {{ $contact->is_read ? 'Sudah Dibaca' : 'Baru' }}
            </span>
        </div>

        <div class="grid md:grid-cols-2 gap-4 mb-6 text-sm">
            <div><strong>Pengirim:</strong> {{ $contact->name }}</div>
            <div><strong>Email:</strong> <a href="mailto:{{ $contact->email }}" class="text-blue-600">{{ $contact->email }}</a></div>
            <div><strong>Dikirim:</strong> {{ $contact->created_at->format('d F Y, H:i') }}</div>
        </div>

        <div class="border-t pt-4">
            <p class="whitespace-pre-wrap text-gray-700">{{ $contact->message }}</p>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}"
               class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800">
                ✉️ Balas via Email
            </a>
            <form action="{{ route('petugas.contact.destroy', $contact) }}" method="POST"
                  onsubmit="return confirm('Hapus pesan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50">
                    🗑️ Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
