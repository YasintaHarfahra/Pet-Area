@extends('layouts.admin_app')

@section('title', 'Laporan Periode - Pet Area Clinic')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.cashier.index') }}" class="inline-flex items-center text-gray-600 hover:text-[#970747] mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Laporan Kasir Periode</h1>
                <p class="text-gray-600">Ringkasan pendapatan dan transaksi</p>
            </div>
            <a href="{{ route('admin.cashier.period-report-pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="mt-4 md:mt-0 px-6 py-2 bg-[#970747] text-white rounded-lg hover:bg-[#7a0538] inline-flex items-center gap-2 font-medium shadow-lg shadow-pink-100 active:scale-95 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    {{-- Period Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form action="{{ route('admin.cashier.period-report') }}" method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#970747]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#970747]">
            </div>
            <button type="submit" class="px-6 py-2 bg-[#970747] text-white rounded-lg hover:bg-[#7a0538]">
                Lihat Laporan
            </button>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-gradient-to-r from-pink-500 to-purple-500 rounded-xl p-5 text-white">
            <p class="text-pink-100 text-sm">Total Transaksi</p>
            <p class="text-3xl font-bold">{{ $summary['total_transaksi'] }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl p-5 text-white">
            <p class="text-green-100 text-sm">Total Pendapatan</p>
            <p class="text-2xl font-bold">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-gray-500 text-sm">Tunai</p>
            <p class="text-xl font-bold text-gray-800">Rp {{ number_format($summary['tunai'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-gray-500 text-sm">Transfer</p>
            <p class="text-xl font-bold text-gray-800">Rp {{ number_format($summary['transfer'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-gray-500 text-sm">QRIS</p>
            <p class="text-xl font-bold text-gray-800">Rp {{ number_format($summary['qris'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">
                Daftar Transaksi - {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
            </h2>
        </div>

        @if($transactions->isEmpty())
        <div class="p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-600 mb-2">Tidak ada transaksi</h3>
            <p class="text-gray-500">Belum ada transaksi lunas pada periode ini</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pemilik</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Metode</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transactions as $index => $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-3 font-medium text-[#970747]">{{ $transaction->nomor_transaksi }}</td>
                        <td class="px-6 py-3">{{ $transaction->medicalRecord->pet->nama_hewan ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $transaction->medicalRecord->pet->owner->nama ?? '-' }}</td>
                        <td class="px-6 py-3 text-center">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $transaction->metode_bayar === 'tunai' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $transaction->metode_bayar === 'transfer' ? 'bg-pink-100 text-[#7a0538]' : '' }}
                                {{ $transaction->metode_bayar === 'qris' ? 'bg-purple-100 text-purple-700' : '' }}
                            ">
                                {{ strtoupper($transaction->metode_bayar) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right font-semibold">Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $transaction->tgl_bayar?->format('H:i') ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-right font-semibold text-gray-700">TOTAL PENDAPATAN:</td>
                        <td class="px-6 py-4 text-right font-bold text-lg text-[#970747]">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
    </div>
</div>

<style>
@media print {
    body {
        background: white !important;
    }
    .no-print, button, a, form {
        display: none !important;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){document.querySelectorAll('form[method="GET"] select,form[method="GET"] input[type="date"]').forEach(function(el){el.addEventListener('change',function(){el.closest('form').submit()})})});
</script>
@endsection
