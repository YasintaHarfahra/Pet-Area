@extends('layouts.admin_app')

@section('title', 'Kasir - Pet Area Clinic')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Menunggu Pembayaran</p>
                    <p class="text-3xl font-bold">{{ $stats['belum_bayar'] }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-400 to-emerald-500 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Lunas Hari Ini</p>
                    <p class="text-3xl font-bold">{{ $stats['lunas_hari_ini'] }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-[#970747] to-purple-500 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold">Rp {{ number_format($stats['total_hari_ini'], 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kasir - Daftar Tagihan</h1>
            <p class="text-gray-600">Kelola pembayaran pasien</p>
        </div>
        <a href="{{ route('admin.cashier.period-report') }}" class="mt-4 md:mt-0 px-6 py-3 bg-[#970747] text-white rounded-lg inline-flex items-center gap-2 font-medium hover:bg-[#7a0538]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Laporan Periode
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form action="{{ route('admin.cashier.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#970747] w-full md:w-40">
                    <option value="">Semua</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#970747]">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-[#970747] text-white rounded-lg hover:bg-[#7a0538]">Filter</button>
                <a href="{{ route('admin.cashier.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($transactions->isEmpty())
        <div class="p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-600 mb-2">Tidak ada transaksi</h3>
            <p class="text-gray-500">Semua tagihan sudah dibayar atau tidak ada data</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pemilik</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-[#970747]">{{ $transaction->nomor_transaksi }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-800">{{ $transaction->medicalRecord->pet->nama_hewan ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->medicalRecord->pet->jenis ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-800">{{ $transaction->medicalRecord->pet->owner->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->medicalRecord->pet->owner->no_hp ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-lg font-bold text-gray-800">Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($transaction->status_bayar === 'paid')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Bayar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col gap-2 items-center">
                                <a href="{{ route('admin.cashier.show', $transaction) }}" 
                                   class="inline-flex items-center px-4 py-2 {{ $transaction->status_bayar === 'paid' ? 'bg-gray-100 text-gray-700' : 'bg-green-500 text-white' }} rounded-lg text-sm font-medium hover:opacity-90 transition">
                                    @if($transaction->status_bayar === 'paid')
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Detail
                                    @else
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Bayar
                                    @endif
                                </a>
                                <form action="{{ route('admin.cashier.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tagihan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-500 text-white rounded-lg text-xs font-medium hover:bg-red-700 transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
        @endif
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){document.querySelectorAll('form[method="GET"] select,form[method="GET"] input[type="date"]').forEach(function(el){el.addEventListener('change',function(){el.closest('form').submit()})})});
</script>
@endsection
