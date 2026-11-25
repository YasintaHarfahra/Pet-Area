@extends('layouts.admin_app')

@section('title', 'Struk Pembayaran - Pet Area Clinic')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.cashier.index') }}" class="inline-flex items-center text-gray-600 hover:text-[#970747]">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
        <button onclick="window.print()" class="px-6 py-2 bg-[#970747] text-white rounded-lg hover:bg-[#7a0538] inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Struk
        </button>
    </div>

    {{-- Receipt Card --}}
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" id="receipt">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#970747] to-purple-600 text-white p-6 text-center">
            <h1 class="text-2xl font-bold">🐾 Pet Area Clinic</h1>
            <p class="text-pink-200 text-sm mt-1">Klinik Hewan Terpercaya</p>
        </div>

        <div class="p-6">
            {{-- Invoice Info --}}
            <div class="text-center pb-4 border-b border-dashed border-gray-300">
                <p class="text-gray-500 text-sm">No. Invoice</p>
                <p class="text-xl font-bold text-gray-800">{{ $transaction->nomor_transaksi ?? '-' }}</p>
                <p class="text-gray-500 text-sm mt-1">{{ $transaction->tgl_bayar?->format('d F Y H:i') ?? now()->format('d F Y H:i') }}</p>
            </div>

            {{-- Patient Info --}}
            <div class="py-4 border-b border-dashed border-gray-300">
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <p class="text-gray-500">Pasien</p>
                        <p class="font-medium">{{ $transaction->medicalRecord->pet->nama_hewan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Jenis</p>
                        <p class="font-medium">{{ $transaction->medicalRecord->pet->jenis ?? '-' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500">Pemilik</p>
                        <p class="font-medium">{{ $transaction->medicalRecord->pet->owner->nama ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="py-4 border-b border-dashed border-gray-300 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Biaya Pemeriksaan</span>
                    <span>Rp {{ number_format($transaction->medicalRecord->biaya_pemeriksaan ?? 0, 0, ',', '.') }}</span>
                </div>
                
                @if(($transaction->medicalRecord->biaya_tindakan ?? 0) > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Biaya Tindakan</span>
                    <span>Rp {{ number_format($transaction->medicalRecord->biaya_tindakan, 0, ',', '.') }}</span>
                </div>
                @endif

                @if($transaction->medicalRecord->prescriptionItems && $transaction->medicalRecord->prescriptionItems->count() > 0)
                <p class="text-xs text-gray-500 font-medium pt-2">Obat-obatan:</p>
                @foreach($transaction->medicalRecord->prescriptionItems as $item)
                <div class="flex justify-between text-sm ml-2">
                    <span class="text-gray-600">{{ $item->medicine->nama_obat ?? '-' }} x{{ $item->jumlah }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
                @endif
            </div>

            {{-- Total --}}
            <div class="py-4 border-b border-dashed border-gray-300">
                <div class="flex justify-between text-lg font-bold">
                    <span>TOTAL</span>
                    <span class="text-[#970747]">Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="py-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Metode Bayar</span>
                    <span class="font-medium uppercase">{{ $transaction->metode_bayar ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Jumlah Bayar</span>
                    <span class="font-medium">Rp {{ number_format($transaction->jumlah_bayar ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Kembalian</span>
                    <span class="font-bold text-green-600">Rp {{ number_format(($transaction->jumlah_bayar ?? 0) - $transaction->total_biaya, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Admin --}}
            <div class="pt-4 border-t border-gray-200 text-center text-sm text-gray-500">
                <p>Admin: {{ $transaction->admin->name ?? '-' }}</p>
            </div>

            {{-- Footer --}}
            <div class="mt-6 text-center">
                <p class="text-gray-400 text-xs">--- Terima kasih atas kunjungan Anda ---</p>
                <p class="text-gray-400 text-xs mt-1">Semoga hewan peliharaan Anda sehat selalu!</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #receipt, #receipt * {
        visibility: visible;
    }
    #receipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 80mm;
        box-shadow: none !important;
        border: none !important;
    }
    .no-print {
        display: none !important;
    }
}
</style>
@endsection
