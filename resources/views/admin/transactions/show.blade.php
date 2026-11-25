@extends('layouts.admin_app')

@section('title', 'Detail Transaksi - Admin')
@section('header_title', 'Detail Transaksi')

@section('content')
    <div class="max-w-2xl mx-auto" 
         x-data="transactionDetail({{ $transaction->total_amount ?? 0 }})">
        {{-- Back Button --}}
        <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 mb-6 group relative z-10">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Daftar</span>
        </a>

        {{-- Receipt Card --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden relative z-0">
            
            {{-- Header with Logo --}}
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-5 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid slice">
                        <text x="10" y="30" font-size="20">🐾</text>
                        <text x="50" y="20" font-size="15">🐾</text>
                        <text x="80" y="40" font-size="18">🐾</text>
                        <text x="30" y="60" font-size="22">🐾</text>
                        <text x="70" y="70" font-size="16">🐾</text>
                    </svg>
                </div>
                
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🐾</span>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-white">Pet Area Clinic</h1>
                            <p class="text-emerald-100 text-sm">Klinik Hewan Terpercaya</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-emerald-100 text-xs">No. Transaksi</p>
                        <p class="text-white font-mono font-bold text-lg">#TRX-{{ str_pad($transaction->id_transaction, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>

            {{-- Status Badge --}}
            <div class="px-6 py-3 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Status Pembayaran</span>
                    @if($transaction->status === 'paid')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-sm font-semibold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            LUNAS
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-sm font-semibold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            PENDING
                        </span>
                    @endif
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="px-6 py-4 border-b border-dashed border-slate-200">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Tanggal</p>
                        <p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($transaction->transaction_date)->translatedFormat('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Kasir</p>
                        <p class="font-semibold text-slate-800">{{ $transaction->admin->name ?? '-' }}</p>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Dokter</p>
                        <p class="font-semibold text-slate-800">{{ $transaction->doctor->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Patient Info --}}
            <div class="px-6 py-4 border-b border-dashed border-slate-200 bg-slate-50/30">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center flex-shrink-0">
                        @php
                            $speciesIcon = match(strtolower($transaction->patient->species ?? '')) {
                                'kucing', 'cat' => '🐱',
                                'anjing', 'dog' => '🐕',
                                'kelinci', 'rabbit' => '🐰',
                                'hamster' => '🐹',
                                'burung', 'bird' => '🐦',
                                default => '🐾'
                            };
                        @endphp
                        <span class="text-2xl">{{ $speciesIcon }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 text-lg">{{ $transaction->patient->name ?? '-' }}</p>
                        <p class="text-sm text-slate-500">{{ $transaction->patient->species ?? '-' }} • {{ $transaction->patient->breed ?? '-' }}</p>
                        <p class="text-sm text-slate-400">Pemilik: <span class="text-slate-600">{{ $transaction->patient->owner_name ?? '-' }}</span></p>
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="px-6 py-4">
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-3">Rincian Layanan</p>
                
                <div class="bg-slate-50 rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="text-left py-3 px-4 font-semibold text-slate-600">Layanan/Obat</th>
                                <th class="text-center py-3 px-4 font-semibold text-slate-600 w-16">Qty</th>
                                <th class="text-right py-3 px-4 font-semibold text-slate-600 w-28">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php
                                $items = [];
                                $desc = $transaction->description ?? '';
                                
                                if (preg_match('/Tindakan:\s*([^,]+)/i', $desc, $treatMatch)) {
                                    $treatments = array_map('trim', explode(';', $treatMatch[1]));
                                    foreach ($treatments as $t) {
                                        if (!empty($t) && $t !== '-') {
                                            $items[] = ['name' => $t, 'type' => 'treatment'];
                                        }
                                    }
                                }
                                if (preg_match('/Obat:\s*(.+)/i', $desc, $medMatch)) {
                                    $medicines = array_map('trim', explode(';', $medMatch[1]));
                                    foreach ($medicines as $m) {
                                        if (!empty($m) && $m !== '-') {
                                            $items[] = ['name' => $m, 'type' => 'medicine'];
                                        }
                                    }
                                }
                                
                                if (empty($items) && !empty($desc)) {
                                    $items[] = ['name' => $desc, 'type' => 'general'];
                                }
                                
                                $itemCount = count($items) ?: 1;
                                $amountPerItem = ($transaction->amount ?? 0) / $itemCount;
                            @endphp
                            
                            @forelse($items as $item)
                                <tr class="bg-white">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            @if($item['type'] === 'treatment')
                                                <span class="text-[#970747]">💉</span>
                                            @elseif($item['type'] === 'medicine')
                                                <span class="text-green-500">💊</span>
                                            @else
                                                <span class="text-slate-400">📋</span>
                                            @endif
                                            <span class="text-slate-700">{{ $item['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-center text-slate-600">1</td>
                                    <td class="py-3 px-4 text-right font-mono text-slate-700">
                                        Rp{{ number_format($amountPerItem, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white">
                                    <td colspan="3" class="py-4 px-4 text-center text-slate-400">
                                        Tidak ada rincian
                                    </td>
                                </tr>
                            @endforelse
                            
                            @if($transaction->service_fee > 0)
                                <tr class="bg-white">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-amber-500">🩺</span>
                                            <span class="text-slate-700">Biaya Jasa/Konsultasi</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-center text-slate-600">1</td>
                                    <td class="py-3 px-4 text-right font-mono text-slate-700">
                                        Rp{{ number_format($transaction->service_fee, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Total Section --}}
            <div class="px-6 py-5 bg-gradient-to-r from-slate-800 to-slate-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm">Total Pembayaran</p>
                        @if($transaction->status === 'paid')
                            <p class="text-emerald-400 text-xs mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Dibayar via {{ ucfirst($transaction->payment_method ?? 'Tunai') }}
                            </p>
                        @endif
                    </div>
                    <p class="text-3xl font-bold font-mono text-white">
                        Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Payment Section (for Pending) --}}
            @if($transaction->status === 'pending')
            <div class="px-6 py-5 bg-amber-50 border-t-2 border-amber-200">
                <form action="{{ route('admin.transactions.process_payment', $transaction->id_transaction) }}" method="POST" id="paymentForm">
                    @csrf
                    
                    {{-- Payment Method Selection --}}
                    <div class="mb-5">
                        <p class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <span class="w-6 h-6 bg-amber-500 text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            Pilih Metode Pembayaran
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="cash" x-model="paymentMethod" class="sr-only peer">
                                <div class="p-3 rounded-xl border-2 border-slate-200 bg-white text-center transition-all 
                                            peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:ring-2 peer-checked:ring-emerald-200
                                            hover:border-slate-300 hover:bg-slate-50 active:scale-95">
                                    <span class="text-2xl block mb-1">💵</span>
                                    <p class="font-semibold text-slate-700 text-sm">Tunai</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="qris" x-model="paymentMethod" class="sr-only peer">
                                <div class="p-3 rounded-xl border-2 border-slate-200 bg-white text-center transition-all 
                                            peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:ring-2 peer-checked:ring-emerald-200
                                            hover:border-slate-300 hover:bg-slate-50 active:scale-95">
                                    <span class="text-2xl block mb-1">📱</span>
                                    <p class="font-semibold text-slate-700 text-sm">QRIS</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="transfer" x-model="paymentMethod" class="sr-only peer">
                                <div class="p-3 rounded-xl border-2 border-slate-200 bg-white text-center transition-all 
                                            peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:ring-2 peer-checked:ring-emerald-200
                                            hover:border-slate-300 hover:bg-slate-50 active:scale-95">
                                    <span class="text-2xl block mb-1">🏦</span>
                                    <p class="font-semibold text-slate-700 text-sm">Transfer</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="card" x-model="paymentMethod" class="sr-only peer">
                                <div class="p-3 rounded-xl border-2 border-slate-200 bg-white text-center transition-all 
                                            peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:ring-2 peer-checked:ring-emerald-200
                                            hover:border-slate-300 hover:bg-slate-50 active:scale-95">
                                    <span class="text-2xl block mb-1">💳</span>
                                    <p class="font-semibold text-slate-700 text-sm">Kartu</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Amount Input (show for cash) --}}
                    <div class="mb-5" x-show="paymentMethod === 'cash'" x-transition>
                        <p class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <span class="w-6 h-6 bg-amber-500 text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            Uang Diterima
                        </p>
                        <div class="bg-white rounded-xl p-4 border border-slate-200">
                            <input type="number" name="paid_amount" x-model="paidAmount" 
                                class="w-full text-2xl font-bold font-mono text-right border-0 focus:ring-0 p-0 text-slate-800 bg-transparent"
                                placeholder="0">
                            <div class="flex gap-2 mt-3">
                                <button type="button" @click="paidAmount = parseInt(paidAmount || 0) + 50000" 
                                    class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-medium transition-colors">+50rb</button>
                                <button type="button" @click="paidAmount = parseInt(paidAmount || 0) + 100000" 
                                    class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-medium transition-colors">+100rb</button>
                                <button type="button" @click="paidAmount = totalAmount" 
                                    class="flex-1 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-lg text-sm font-medium transition-colors">Pas</button>
                            </div>
                            
                            {{-- Change Display --}}
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <div class="flex justify-between items-center" :class="changeAmount >= 0 ? 'text-emerald-600' : 'text-red-600'">
                                    <span class="text-sm font-medium" x-text="changeAmount >= 0 ? 'Kembalian' : 'Kurang'"></span>
                                    <span class="text-xl font-bold font-mono" x-text="'Rp' + Math.abs(changeAmount).toLocaleString('id-ID')"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- For non-cash, set paid_amount to total --}}
                    <template x-if="paymentMethod !== 'cash'">
                        <input type="hidden" name="paid_amount" :value="totalAmount">
                    </template>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 mt-5">
                        <a href="{{ route('admin.transactions.edit', $transaction->id_transaction) }}" 
                           class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-slate-300 bg-white text-slate-600 font-semibold hover:bg-slate-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <button type="submit" 
                            :disabled="!paymentMethod || (paymentMethod === 'cash' && changeAmount < 0)"
                            :class="!paymentMethod || (paymentMethod === 'cash' && changeAmount < 0) 
                                ? 'bg-slate-300 cursor-not-allowed' 
                                : 'bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 shadow-lg shadow-emerald-200'"
                            class="flex-[2] inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-white font-bold transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span x-text="paymentMethod ? 'Konfirmasi Pembayaran' : 'Pilih Metode Dulu'"></span>
                        </button>
                    </div>
                </form>
            </div>
            @else
            {{-- Paid: Show Print Button --}}
            <div class="px-6 py-5 bg-slate-50 border-t border-slate-100">
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.transactions.index') }}" 
                       class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-slate-300 text-slate-600 font-semibold hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Daftar Transaksi
                    </a>
                    <button type="button" @click="printReceipt()" 
                        class="flex-[2] inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-[#970747] to-purple-600 text-white font-bold shadow-lg shadow-pink-200 hover:shadow-xl hover:from-[#970747] hover:to-purple-700 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Struk
                    </button>
                </div>
            </div>
            @endif

            {{-- Footer --}}
            <div class="px-6 py-3 bg-slate-100 text-center border-t border-slate-200">
                <p class="text-xs text-slate-400">Terima kasih telah mempercayakan perawatan anabul Anda kepada kami 🐾</p>
            </div>
        </div>
    </div>

    {{-- Receipt Print Template --}}
    <div id="receiptContent" class="hidden">
        <div style="width:80mm;font-family:monospace;font-size:12px;padding:10px;">
            <div style="text-align:center;border-bottom:2px double #000;padding-bottom:10px;margin-bottom:10px;">
                <b style="font-size:14px;">🐾 PET AREA CLINIC</b><br>
                <span style="font-size:10px;">Klinik Hewan Terpercaya</span><br>
                <span style="font-size:9px;">Jl. Kesehatan No. 123 | 0812-3456-7890</span>
            </div>
            <div style="font-size:10px;margin-bottom:10px;">
                <div>No: #TRX-{{ str_pad($transaction->id_transaction, 5, '0', STR_PAD_LEFT) }}</div>
                <div>Tgl: {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</div>
                <div>Pasien: {{ $transaction->patient->name ?? '-' }}</div>
                <div>Pemilik: {{ $transaction->patient->owner_name ?? '-' }}</div>
            </div>
            <div style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:8px 0;margin:8px 0;font-size:10px;">
                {{ $transaction->description ?? '-' }}
            </div>
            <div style="font-size:11px;">
                @if($transaction->amount > 0)
                <div style="display:flex;justify-content:space-between;">
                    <span>Layanan</span><span>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($transaction->service_fee > 0)
                <div style="display:flex;justify-content:space-between;">
                    <span>Jasa</span><span>Rp{{ number_format($transaction->service_fee, 0, ',', '.') }}</span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;font-weight:bold;font-size:13px;margin-top:8px;padding-top:8px;border-top:1px solid #000;">
                    <span>TOTAL</span><span>Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
            <div style="text-align:center;margin-top:15px;font-size:10px;border-top:1px dashed #000;padding-top:10px;">
                <div>Metode: {{ ucfirst($transaction->payment_method ?? 'Tunai') }}</div>
                <div>Kasir: {{ $transaction->admin->name ?? '-' }}</div>
                <br>
                <b>✨ Terima Kasih ✨</b><br>
                <span style="font-size:9px;color:#666;">Semoga Anabul Sehat Selalu!</span>
            </div>
        </div>
    </div>

    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function transactionDetail(totalAmount) {
            return {
                paymentMethod: '',
                paidAmount: totalAmount,
                totalAmount: totalAmount,
                
                get changeAmount() {
                    return (parseInt(this.paidAmount) || 0) - this.totalAmount;
                },
                
                printReceipt() {
                    const w = window.open('', '', 'width=350,height=500');
                    w.document.write('<html><body>' + document.getElementById('receiptContent').innerHTML + '</body></html>');
                    w.document.close();
                    w.onload = function() { w.print(); w.close(); };
                }
            }
        }
    </script>
@endsection