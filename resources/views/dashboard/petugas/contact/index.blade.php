@extends('dashboard.app')

@section('title', '📩 Pesan Kontak')

@section('content')
<div class="p-6">
 <h1 class="text-2xl font-bold">PESAN  <span class="text-blue-600">MASUK</span></h1>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
    @endif

    {{-- Filter & Search --}}
    <form method="GET" class="mb-6 flex flex-wrap gap-4">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama, email, subjek..."
               class="px-4 py-2 border rounded-lg flex-1 min-w-[200px]">

        <select name="status" class="px-4 py-2 border rounded-lg">
            <option value="">Semua Status</option>
            <option value="unread" {{ request('status')==='unread'?'selected':'' }}>Belum Dibaca</option>
            <option value="read" {{ request('status')==='read'?'selected':'' }}>Sudah Dibaca</option>
        </select>

        <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800">🔍 Cari</button>
        <a href="{{ route('petugas.contact.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-100">Reset</a>
    </form>

    {{-- Bulk Delete --}}
    <form id="bulkDeleteForm" action="{{ route('petugas.contact.destroyAll') }}" method="POST" class="mb-4">
        @csrf @method('DELETE')
        <button type="button" onclick="deleteSelected()"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
            🗑️ Hapus Terpilih
        </button>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-left"><input type="checkbox" id="selectAll"></th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Pengirim</th>
                    <th class="p-4 text-left">Subject</th>
                    <th class="p-4 text-left">Tanggal</th>
                    <th class="p-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr class="border-t hover:bg-gray-50 {{ !$contact->is_read ? 'bg-blue-50' : '' }}">
                    <td class="p-4"><input type="checkbox" name="ids[]" value="{{ $contact->id }}" class="contact-checkbox"></td>
                    <td class="p-4">
                        @if(!$contact->is_read)
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Baru</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">Dibaca</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="font-medium">{{ $contact->name }}</div>
                        <div class="text-sm text-gray-500">{{ $contact->email }}</div>
                    </td>
                    <td class="p-4">{{ Str::limit($contact->subject, 40) }}</td>
                    <td class="p-4 text-sm text-gray-500">{{ $contact->created_at->format('d M Y, H:i') }}</td>
                    <td class="p-4">
                        <a href="{{ route('petugas.contact.show', $contact) }}"
                           class="text-blue-600 hover:underline mr-3">Lihat</a>
                        <form action="{{ route('petugas.contact.destroy', $contact) }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus pesan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500">📭 Belum ada pesan kontak.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $contacts->links() }}
    </div>
</div>

<script>
// Select All Checkbox
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.contact-checkbox').forEach(cb => cb.checked = this.checked);
});

// Bulk Delete
function deleteSelected() {
    const checked = document.querySelectorAll('.contact-checkbox:checked');
    if (checked.length === 0) {
        alert('⚠️ Pilih minimal satu pesan untuk dihapus.');
        return;
    }
    if (!confirm(`Hapus ${checked.length} pesan terpilih?`)) return;

    const form = document.getElementById('bulkDeleteForm');
    checked.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = cb.value;
        form.appendChild(input);
    });
    form.submit();
}
</script>
@endsection
