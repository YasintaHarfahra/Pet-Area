@extends('layouts.admin_app')

@section('title', 'Proses Pembayaran - Pet Area Clinic')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 font-sans">
    
    {{-- Header Page --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.cashier.index') }}" class="p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition shadow-sm">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Kasir & Pembayaran</h1>
            <p class="text-sm text-gray-500">No. Invoice: <span class="font-mono font-bold">{{ $transaction->nomor_transaksi ?? '-' }}</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {{-- KOLOM KIRI: RINCIAN (8 Kolom) --}}
        <div class="lg:col-span-8 space-y-6">
            
            {{-- Info Pasien & Pemilik --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 border-b pb-2">Informasi Pasien</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Nama Hewan</p>
                        <p class="font-bold text-gray-800">{{ $transaction->medicalRecord->pet->nama_hewan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Jenis/Ras</p>
                        <p class="font-bold text-gray-800 text-sm">{{ $transaction->medicalRecord->pet->jenis ?? '' }} / {{ $transaction->medicalRecord->pet->ras ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Pemilik</p>
                        <p class="font-bold text-gray-800">{{ $transaction->medicalRecord->pet->owner->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold mb-1">WhatsApp</p>
                        <p class="font-bold text-gray-800">{{ $transaction->medicalRecord->pet->owner->no_hp ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Rincian Biaya --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Rincian Layanan & Obat</h2>
                </div>

                <div class="p-6 space-y-4">

                    {{-- Biaya Pemeriksaan --}}
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 font-medium">Jasa Pemeriksaan Dokter</span>
                        <span class="font-mono font-bold text-gray-800">Rp {{ number_format($transaction->medicalRecord->biaya_pemeriksaan ?? 0, 0, ',', '.') }}</span>
                    </div>

                    {{-- Rincian Treatment (Tindakan) --}}
                    @php
                        $totalTindakan = 0;
                    @endphp
                    @if($transaction->medicalRecord->treatments && $transaction->medicalRecord->treatments->count() > 0)
                        <div class="pt-2 pb-2">
                            <p class="text-xs font-bold text-slate-400 uppercase mb-2 tracking-widest">Tindakan Medis</p>
                            @foreach($transaction->medicalRecord->treatments as $treatment)
                                @php $totalTindakan += $treatment->pivot->harga ?? 0; @endphp
                                <div class="flex justify-between items-center text-sm mb-2 pl-4 border-l-2 border-pink-100">
                                    <span class="text-gray-600">{{ $treatment->nama_tindakan }}</span>
                                    <span class="font-mono text-gray-700 font-semibold text-xs">Rp {{ number_format($treatment->pivot->harga ?? 0, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 font-medium">Biaya Tindakan Medis</span>
                        <span class="font-mono font-bold text-gray-800">Rp {{ number_format($totalTindakan, 0, ',', '.') }}</span>
                    </div>

                    {{-- Rincian Obat --}}
                    @php
                        $subtotalObat = 0;
                    @endphp
                    @if($transaction->medicalRecord->prescriptionItems && $transaction->medicalRecord->prescriptionItems->count() > 0)
                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-xs font-bold text-slate-400 uppercase mb-3 tracking-widest">Daftar Obat-obatan</p>
                            @foreach($transaction->medicalRecord->prescriptionItems as $item)
                                @php $subtotalObat += $item->subtotal ?? 0; @endphp
                                <div class="flex justify-between items-center text-sm mb-3 pl-4 border-l-2 border-indigo-100">
                                    <span class="text-gray-600">{{ $item->medicine->nama_obat ?? '-' }} <span class="text-xs text-gray-400 ml-2">x{{ $item->jumlah }}</span></span>
                                    <span class="font-mono text-gray-700 font-semibold text-xs">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal Obat</span>
                        <span class="font-mono italic">Rp {{ number_format($subtotalObat, 0, ',', '.') }}</span>
                    </div>

                    {{-- Ringkasan Total --}}
                    <div class="mt-6 pt-6 border-t-2 border-gray-100 space-y-2">
                        <div class="flex justify-between text-gray-500">
                            <span>Subtotal Obat</span>
                            <span class="font-mono italic">Rp {{ number_format($transaction->medicalRecord->biaya_obat ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xl font-black pt-2">
                            <span class="text-gray-800 tracking-tighter uppercase">Total Tagihan</span>
                            <span class="text-[#970747] font-mono tracking-tighter">Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Kasir (Jika Sudah Lunas) --}}
            @if($transaction->status_bayar === 'paid')
            <div class="bg-emerald-50 rounded-xl border border-emerald-100 p-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <div>
                        <p class="text-emerald-800 font-bold uppercase tracking-tight text-sm">Pembayaran Berhasil</p>
                        <p class="text-xs text-emerald-600 font-medium">Melalui {{ $transaction->metode_bayar }} oleh Admin: {{ $transaction->admin->name ?? '-' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-emerald-500 uppercase italic leading-none mb-1">Tanggal Bayar</p>
                    <p class="text-sm font-bold text-emerald-800">{{ $transaction->tgl_bayar?->format('d/m/Y H:i') ?? '-' }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- KOLOM KANAN: FORM BAYAR (4 Kolom) --}}
        <div class="lg:col-span-4 space-y-6">
            @if($transaction->status_bayar !== 'paid')
                {{-- Form Proses --}}
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sticky top-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#970747]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Selesaikan Pembayaran
                    </h2>
                    
                    <form action="{{ route('admin.cashier.pay', $transaction) }}" method="POST" id="paymentForm">
                        @csrf
                        <div class="space-y-6">
                            {{-- Pilih Metode --}}
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 block">Metode Pembayaran</label>
                                <div class="grid grid-cols-1 gap-2">
                                    <label class="relative flex items-center px-4 py-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition peer-checked:border-[#970747]">
                                        <input type="radio" name="metode_bayar" value="tunai" class="w-4 h-4 text-[#970747] focus:ring-[#970747]" required>
                                        <span class="ml-3 text-sm font-bold text-gray-700">Tunai / Cash</span>
                                    </label>
                                    <label class="relative flex items-center px-4 py-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" name="metode_bayar" value="transfer" class="w-4 h-4 text-[#970747] focus:ring-[#970747]">
                                        <span class="ml-3 text-sm font-bold text-gray-700">Bank Transfer</span>
                                    </label>
                                    <label class="relative flex items-center px-4 py-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" name="metode_bayar" value="qris" class="w-4 h-4 text-[#970747] focus:ring-[#970747]">
                                        <span class="ml-3 text-sm font-bold text-gray-700">QRIS</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Input Nominal --}}
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 block">Nominal Bayar</label>
                                <div class="relative">
                                     <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400">Rp</span>
                                     <input type="number" name="jumlah_bayar" id="jumlahBayar" 
                                         class="w-full pl-12 pr-4 py-4 text-2xl font-black bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#970747]"
                                         min="0" 
                                         value="{{ $transaction->total_biaya }}" 
                                         required oninput="calculateChange()">
                                </div>
                            </div>

                            {{-- Kembalian --}}
                            <div id="changeSection" class="p-4 bg-emerald-50 rounded-2xl hidden">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-emerald-600 uppercase">Kembalian</span>
                                    <span id="changeAmount" class="text-xl font-black text-emerald-800 font-mono">Rp 0</span>
                                </div>
                            </div>

                            {{-- Tombol Cepat --}}
                            <div class="grid grid-cols-3 gap-2">
                                {{-- Tombol cepat dihapus, admin bisa input nominal bebas --}}
                            </div>

                            <button type="submit" class="w-full bg-[#970747] hover:bg-[#7a0538] text-white py-4 rounded-2xl font-bold shadow-lg shadow-pink-200 transition-all uppercase tracking-widest">
                                Proses Lunas
                            </button>
                        </div>
                    </form>
                </div>
            @else
                {{-- Aksi Sesudah Lunas --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                    <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Opsi Lanjutan</h2>
                    <div class="space-y-3">
                        <button type="button" onclick="openPrintReceipt()"
                           class="w-full py-4 bg-slate-900 text-white rounded-xl flex items-center justify-center gap-2 font-bold hover:bg-black transition shadow-lg uppercase text-xs tracking-widest">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak Struk
                        </button>
                        @push('scripts')
                        <script>
                        function openPrintReceipt() {
                            const url = '{{ route('admin.cashier.receipt', $transaction) }}';
                            const printWindow = window.open(url, '_blank');
                            printWindow.onload = function() {
                                setTimeout(() => printWindow.print(), 500);
                            };
                        }
                        </script>
                        @endpush
                        <a href="{{ route('admin.cashier.index') }}" 
                           class="w-full py-4 bg-gray-100 text-gray-600 rounded-xl flex items-center justify-center gap-2 font-bold hover:bg-gray-200 transition uppercase text-xs tracking-widest">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Script tetap sama tapi pastikan data diambil dengan aman --}}
@push('scripts')
<script>
    const totalBiaya = {{ (int) $transaction->total_biaya }};

    function setAmount(amount) {
        document.getElementById('jumlahBayar').value = amount;
        calculateChange();
    }

    function calculateChange() {
        const input = document.getElementById('jumlahBayar');
        const jumlahBayar = parseInt(input.value) || 0;
        const change = jumlahBayar - totalBiaya;
        const section = document.getElementById('changeSection');
        const amountDisplay = document.getElementById('changeAmount');

        if (change >= 0) {
            section.classList.remove('hidden');
            amountDisplay.textContent = 'Rp ' + change.toLocaleString('id-ID');
        } else {
            section.classList.add('hidden');
        }
    }

    // Jalankan sekali saat load
    document.addEventListener('DOMContentLoaded', calculateChange);
</script>
@endpush
@endsection