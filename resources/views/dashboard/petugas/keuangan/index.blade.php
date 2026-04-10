@extends('dashboard.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER & TOTAL --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Laporan Keuangan</h1>
                <p class="text-sm text-gray-500">Rekapitulasi denda yang telah lunas dibayarkan.</p>
            </div>

            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-green-50 rounded-xl text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Pendapatan</p>
                    <h2 class="text-xl font-black text-gray-800">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>

        {{-- FILTER SEARCH --}}
        <form method="GET" class="mb-6 flex gap-3 flex-wrap items-end bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Cari Anggota / Buku</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama..."
                    class="border border-gray-200 p-2 rounded-xl w-48 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Dari</label>
                <input type="date" name="from" value="{{ request('from') }}" class="border border-gray-200 p-2 rounded-xl text-sm outline-none">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Sampai</label>
                <input type="date" name="to" value="{{ request('to') }}" class="border border-gray-200 p-2 rounded-xl text-sm outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-md shadow-blue-100 transition-all">Filter</button>
                <a href="{{ route('keuangan') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-5 py-2 rounded-xl text-sm font-bold transition-all">Reset</a>
            </div>
        </form>

        {{-- CHART --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <div class="h-64"><canvas id="chartKeuangan"></canvas></div>
        </div>

        {{-- TABLE DATA --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 w-12 text-center">No</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Anggota</th>
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4 text-center">Stok</th>
                        <th class="px-6 py-4 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $i => $item)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4 text-center text-gray-400 text-xs">{{ $i+1 }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-700">
                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                        </td>

                        {{-- AMBIL DATA DARI RELASI PEMINJAMAN --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full overflow-hidden border border-gray-100 flex-shrink-0 shadow-sm">
                                    @if($item->peminjaman?->user?->photo)
                                        <img src="{{ asset('storage/' . $item->peminjaman->user->photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-blue-600 flex items-center justify-center text-white font-bold text-[10px]">
                                            {{ strtoupper(substr($item->peminjaman?->user?->name ?? '?', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-700 leading-none mb-1">
                                        {{ $item->peminjaman?->user?->name ?? 'User Terhapus' }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">
                                        NIS: {{ $item->peminjaman?->user?->anggota?->nis ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-600 text-sm">
                            {{ $item->peminjaman?->buku?->judul ?? 'Buku Dihapus' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            @php $stok = $item->peminjaman?->buku?->stok ?? 0; @endphp
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $stok <= 2 ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }}">
                                {{ $stok }} buku
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black text-green-600">
                                Rp {{ number_format($item->total_denda, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-20 text-gray-400">Belum ada data pendapatan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('chartKeuangan').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($chart->pluck('total')) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.05)',
                    fill: true,
                    borderWidth: 3,
                    tension: 0.4
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { font: { size: 10 } } },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });
    });
</script>
@endsection
