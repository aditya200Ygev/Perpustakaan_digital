@extends('dashboard.app')

@section('title', 'Data Denda')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER SECTION --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Manajemen Denda</h1>
                <p class="text-sm text-gray-500">Konfirmasi pembayaran denda dari anggota perpustakaan.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('keuangan') }}"
                   class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-md shadow-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Lihat Laporan Keuangan
                </a>
            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 bg-white flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800">Daftar Tunggu Pembayaran</h2>
                <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                    {{ $denda->count() }} Transaksi
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-400 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Anggota</th>
                            <th class="px-6 py-4">Informasi Buku</th>
                            <th class="px-6 py-4">Tgl Kembali</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($denda as $i => $item)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">{{ $i+1 }}</td>

                            {{-- 👤 KOLOM ANGGOTA --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 border border-gray-100 shadow-sm">
                                        @if($item->user->photo)
                                            <img src="{{ asset('storage/' . $item->user->photo) }}"
                                                 class="w-full h-full object-cover"
                                                 alt="{{ $item->user->name }}">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($item->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-700">{{ $item->user->name }}</span>
                                        <span class="text-xs text-gray-400">{{ $item->user->email }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- 📚 KOLOM BUKU --}}
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-700 truncate max-w-xs">{{ $item->buku->judul }}</p>
                                <p class="text-xs text-gray-400 font-mono">ID: #{{ $item->buku_id }}</p>
                            </td>

                            {{-- 📅 KOLOM TANGGAL --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($item->tgl_kembali)->translatedFormat('d M Y') }}</span>
                                    <span class="text-[10px] text-red-500 font-bold uppercase italic">
                                        Terlambat {{ max(0, \Carbon\Carbon::parse($item->tgl_kembali)->diffInDays(\Carbon\Carbon::now())) }} Hari
                                    </span>
                                </div>
                            </td>
{{-- KOLOM STATUS --}}
<td class="px-6 py-4 text-center">
    @if(!$item->is_paid)
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-600 border border-gray-200">
            ⏳ Menunggu Pembayaran
        </span>
    @elseif($item->is_paid && $item->status == 'denda')
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">
            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500 animate-pulse"></span>
            Menunggu ACC
        </span>
    @elseif($item->is_paid && $item->status == 'selesai')
        {{-- ✅ DENDA LUNAS YANG SUDAH DIPROSES --}}
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-100">
            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span>
            ✅ Lunas
        </span>
    @else
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">
            -
        </span>
    @endif
</td>

                            {{-- ⚙️ KOLOM AKSI (FIXED) --}}
                            <td class="px-6 py-4 text-right">
                                {{-- ✅ TOMBOL KONFIRMASI: Hanya muncul jika anggota SUDAH klik "Ajukan Bayar" --}}
                                @if($item->is_paid && $item->status == 'denda')
                                    <form action="{{ route('petugas.acc.denda', $item->id) }}"
                                          method="POST"
                                          class="inline-block form-confirm">
                                        @csrf
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-md shadow-green-100 active:scale-95">
                                            ✅ Konfirmasi
                                        </button>
                                    </form>

                                {{-- ✅ SUDAH DIPROSES / BELUM DIAJUKAN --}}
                                @else
                                    <div class="flex items-center justify-end text-gray-400 gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-xs font-medium italic">Selesai</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-20">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-bold text-lg">Semua Tagihan Beres!</p>
                                    <p class="text-gray-400 text-sm">Tidak ada denda yang perlu dikonfirmasi saat ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- SWEETALERT 2 SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Toast Notification
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    // Konfirmasi Klik ACC
    document.querySelectorAll('.form-confirm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Bayar?',
                text: "Pastikan Anda telah menerima pembayaran tunai dari anggota.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Sudah Bayar',
                cancelButtonText: 'Batal',
                borderRadius: '15px'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>

@endsection
