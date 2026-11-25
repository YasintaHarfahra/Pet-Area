@extends('layouts.admin_app')

@section('title', 'Kasir - Transaksi')
@section('header_title', 'Kasir')

@section('content')
    <div class="max-w-6xl mx-auto px-4 pb-8">
        {{-- Header Compact --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Daftar Transaksi</h1>
                <p class="text-sm text-slate-500">{{ $pendingCount }} menunggu pembayaran</p>
            </div>
            <a href="{{ route('admin.transactions.create') }}" 
               class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium text-sm transition-colors">
                + Transaksi Baru
            </a>
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 border border-slate-200">
                <p class="text-2xl font-bold text-slate-800">{{ $transactions->total() }}</p>
                <p class="text-xs text-slate-500">Total</p>
            </div>
            <div class="bg-amber-50 rounded-xl p-4 border border-amber-200">
                <p class="text-2xl font-bold text-amber-600">{{ $pendingCount }}</p>
                <p class="text-xs text-amber-600">Pending</p>
            </div>
            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                <p class="text-2xl font-bold text-emerald-600">{{ $paidCount }}</p>
                <p class="text-xs text-emerald-600">Lunas</p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4 text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex gap-3 mb-6">
            <input type="text" name="search" value="{{ $search }}" 
                placeholder="Cari nama pasien..." 
                class="flex-1 px-4 py-2 rounded-lg border border-slate-200 text-sm focus:border-[#970747] focus:ring-1 focus:ring-[#970747]">
            <select name="status" class="px-3 py-2 rounded-lg border border-slate-200 text-sm bg-white">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Lunas</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-medium transition-colors">
                Filter
            </button>
        </form>

        {{-- Transaction List - Card Style --}}
        <div class="space-y-3">
            @forelse ($transactions as $transaction)
                <div class="bg-white rounded-xl border {{ $transaction->status === 'pending' ? 'border-amber-200' : 'border-slate-200' }} p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        {{-- Left: Patient & Info --}}
                        <div class="flex items-center gap-4">
                            {{-- Avatar --}}
                            <div class="w-12 h-12 rounded-full {{ $transaction->status === 'pending' ? 'bg-amber-100' : 'bg-slate-100' }} flex items-center justify-center flex-shrink-0">
                                <span class="text-lg font-bold {{ $transaction->status === 'pending' ? 'text-amber-600' : 'text-slate-600' }}">
                                    {{ strtoupper(substr($transaction->patient->name ?? 'X', 0, 1)) }}
                                </span>
                            </div>

                            {{-- Info --}}
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-slate-800">{{ $transaction->patient->name ?? '-' }}</h3>
                                    @if($transaction->status === 'pending')
                                        <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded">Pending</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-700 rounded">Lunas</span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-500">
                                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }} · 
                                    {{ Str::limit($transaction->description, 30) }}
                                </p>
                            </div>
                        </div>

                        {{-- Right: Amount & Actions --}}
                        <div class="flex items-center gap-4">
                            {{-- Amount --}}
                            <div class="text-right">
                                <p class="text-lg font-bold font-mono text-slate-800">
                                    Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2">
                                @if($transaction->status === 'pending')
                                    <button onclick="openPaymentModal('{{ $transaction->id_transaction }}')" 
                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium text-sm transition-colors">
                                        Bayar
                                    </button>
                                @else
                                    <button onclick="printReceiptDirect('{{ $transaction->id_transaction }}')"
                                        class="px-4 py-2 border border-slate-300 hover:bg-slate-50 text-slate-600 rounded-lg text-sm transition-colors">
                                        Cetak
                                    </button>
                                @endif
                                <a href="{{ route('admin.transactions.show', $transaction->id_transaction) }}" 
                                   class="p-2 hover:bg-slate-100 rounded-lg text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <p class="text-slate-600 font-medium">Tidak ada transaksi</p>
                    <p class="text-sm text-slate-400">Mulai dengan mencatat transaksi baru</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($transactions->hasPages())
            <div class="mt-6">
                {{ $transactions->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

    {{-- Modal Pembayaran Simpel --}}
    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50" onclick="closePaymentModal()"></div>
            
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md">
                {{-- Header --}}
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-bold text-slate-800">Proses Pembayaran</h3>
                    <button onclick="closePaymentModal()" class="p-1 hover:bg-slate-100 rounded-lg">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-4">
                    {{-- Info Transaksi --}}
                    <div class="bg-slate-50 rounded-xl p-4 mb-4">
                        <p id="patientName" class="font-semibold text-slate-800">-</p>
                        <p id="diagnosis" class="text-sm text-slate-500 mb-3">-</p>
                        <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                            <span class="text-slate-600">Total</span>
                            <span id="totalAmount" class="text-xl font-bold font-mono text-slate-800">Rp0</span>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form id="paymentForm" onsubmit="submitPayment(event)" class="space-y-4">
                        @csrf
                        <input type="hidden" id="transactionId">

                        {{-- Metode Bayar --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Metode</label>
                            <div class="grid grid-cols-4 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="cash" class="sr-only peer" required>
                                    <div class="p-2 rounded-lg border-2 border-slate-200 text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:bg-slate-50">
                                        <span class="text-lg">💵</span>
                                        <p class="text-xs text-slate-600">Tunai</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="transfer" class="sr-only peer">
                                    <div class="p-2 rounded-lg border-2 border-slate-200 text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:bg-slate-50">
                                        <span class="text-lg">🏦</span>
                                        <p class="text-xs text-slate-600">Transfer</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="card" class="sr-only peer">
                                    <div class="p-2 rounded-lg border-2 border-slate-200 text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:bg-slate-50">
                                        <span class="text-lg">💳</span>
                                        <p class="text-xs text-slate-600">Kartu</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="qris" class="sr-only peer">
                                    <div class="p-2 rounded-lg border-2 border-slate-200 text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:bg-slate-50">
                                        <span class="text-lg">📱</span>
                                        <p class="text-xs text-slate-600">QRIS</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Uang Diterima --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Uang Diterima</label>
                            <input type="number" id="paidAmount" required 
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 text-lg font-mono font-bold text-right focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                                oninput="calculateChange()">
                            <div class="flex gap-2 mt-2">
                                <button type="button" onclick="addAmount(50000)" class="flex-1 py-1.5 bg-slate-100 hover:bg-slate-200 rounded text-xs font-medium">+50rb</button>
                                <button type="button" onclick="addAmount(100000)" class="flex-1 py-1.5 bg-slate-100 hover:bg-slate-200 rounded text-xs font-medium">+100rb</button>
                                <button type="button" onclick="setExact()" class="flex-1 py-1.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded text-xs font-medium">Pas</button>
                            </div>
                        </div>

                        {{-- Kembalian --}}
                        <div class="bg-emerald-50 rounded-lg p-3 border border-emerald-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-emerald-700">Kembalian</span>
                                <span id="changeAmount" class="text-lg font-bold font-mono text-emerald-700">Rp0</span>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex gap-3 pt-2">
                            <button type="button" onclick="closePaymentModal()" 
                                class="flex-1 py-3 border border-slate-300 rounded-lg font-medium hover:bg-slate-50">
                                Batal
                            </button>
                            <button type="submit" id="submitBtn"
                                class="flex-[2] py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium disabled:opacity-50">
                                Bayar & Cetak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Receipt Template --}}
    <div id="receiptContent" class="hidden">
        <div style="width:80mm;font-family:monospace;font-size:12px;padding:10px;">
            <div style="text-align:center;border-bottom:2px double #000;padding-bottom:10px;margin-bottom:10px;">
                <b style="font-size:14px;">PET AREA CLINIC</b><br>
                <span style="font-size:10px;">Jl. Kesehatan No. 123 | 0812-3456-7890</span>
            </div>
            <div style="font-size:10px;margin-bottom:10px;">
                <div>No: <span id="rTransId">-</span></div>
                <div>Tgl: <span id="rDate">-</span></div>
                <div>Pasien: <span id="rPatient">-</span></div>
            </div>
            <div style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:8px 0;margin:8px 0;font-size:10px;">
                <div id="rDesc">-</div>
            </div>
            <div style="font-size:11px;">
                <div style="display:flex;justify-content:space-between;font-weight:bold;font-size:13px;">
                    <span>TOTAL</span><span id="rTotal">Rp0</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span>Bayar</span><span id="rPaid">Rp0</span>
                </div>
                <div style="display:flex;justify-content:space-between;background:#eee;padding:4px;margin-top:4px;">
                    <span>Kembali</span><span id="rChange">Rp0</span>
                </div>
            </div>
            <div style="text-align:center;margin-top:15px;font-size:10px;border-top:1px dashed #000;padding-top:10px;">
                <b>Terima Kasih!</b><br>
                <span style="font-size:9px;color:#666;">{{ date('Y') }} © Pet Area Clinic</span>
            </div>
        </div>
    </div>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        let totalAmount = 0;
        toastr.options = { closeButton: true, progressBar: true, positionClass: "toast-top-right", timeOut: 2000 };

        function formatRp(n) { return 'Rp' + Math.round(n).toLocaleString('id-ID'); }

        function calculateChange() {
            const paid = parseFloat(document.getElementById('paidAmount').value || 0);
            const change = paid - totalAmount;
            document.getElementById('changeAmount').textContent = formatRp(Math.max(0, change));
            document.getElementById('submitBtn').disabled = paid < totalAmount;
        }

        function addAmount(n) {
            const input = document.getElementById('paidAmount');
            input.value = (parseFloat(input.value || 0) + n);
            calculateChange();
        }

        function setExact() {
            document.getElementById('paidAmount').value = totalAmount;
            calculateChange();
        }

        function openPaymentModal(id) {
            document.getElementById('paymentForm').reset();
            fetch(`/admin/transactions/${id}/data`)
                .then(r => r.json())
                .then(t => {
                    totalAmount = t.total_amount || 0;
                    document.getElementById('transactionId').value = id;
                    document.getElementById('patientName').textContent = t.patient?.name || '-';
                    document.getElementById('diagnosis').textContent = t.description || '-';
                    document.getElementById('totalAmount').textContent = formatRp(totalAmount);
                    document.getElementById('paidAmount').value = totalAmount;
                    calculateChange();
                    document.getElementById('paymentModal').classList.remove('hidden');
                })
                .catch(e => toastr.error('Gagal memuat data'));
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }

        function submitPayment(e) {
            e.preventDefault();
            const paid = parseFloat(document.getElementById('paidAmount').value || 0);
            const method = document.querySelector('input[name="payment_method"]:checked')?.value;
            
            if (!method) { toastr.warning('Pilih metode pembayaran'); return; }
            if (paid < totalAmount) { toastr.error('Pembayaran kurang'); return; }

            const id = document.getElementById('transactionId').value;
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = 'Memproses...';

            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('payment_method', method);
            fd.append('paid_amount', paid);

            fetch(`/admin/transactions/${id}/process-payment`, {
                method: 'POST', body: fd,
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(res => {
                if (res.success || res.message) {
                    // Update receipt
                    document.getElementById('rTransId').textContent = '#' + id;
                    document.getElementById('rDate').textContent = new Date().toLocaleDateString('id-ID');
                    document.getElementById('rPatient').textContent = document.getElementById('patientName').textContent;
                    document.getElementById('rDesc').textContent = document.getElementById('diagnosis').textContent;
                    document.getElementById('rTotal').textContent = formatRp(totalAmount);
                    document.getElementById('rPaid').textContent = formatRp(paid);
                    document.getElementById('rChange').textContent = formatRp(paid - totalAmount);
                    
                    toastr.success('Pembayaran berhasil!');
                    printReceipt();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    throw new Error(res.message || 'Gagal');
                }
            })
            .catch(err => {
                toastr.error(err.message);
                btn.disabled = false;
                btn.textContent = 'Bayar & Cetak';
            });
        }

        function printReceipt() {
            const w = window.open('', '', 'width=350,height=500');
            w.document.write('<html><body>' + document.getElementById('receiptContent').innerHTML + '</body></html>');
            w.document.close();
            w.onload = () => { w.print(); w.close(); };
        }

        function printReceiptDirect(id) {
            fetch(`/admin/transactions/${id}/data`)
                .then(r => r.json())
                .then(t => {
                    document.getElementById('rTransId').textContent = '#' + id;
                    document.getElementById('rDate').textContent = new Date(t.transaction_date).toLocaleDateString('id-ID');
                    document.getElementById('rPatient').textContent = t.patient?.name || '-';
                    document.getElementById('rDesc').textContent = t.description || '-';
                    document.getElementById('rTotal').textContent = formatRp(t.total_amount || 0);
                    document.getElementById('rPaid').textContent = formatRp(t.total_amount || 0);
                    document.getElementById('rChange').textContent = 'Rp0';
                    printReceipt();
                })
                .catch(() => toastr.error('Gagal memuat data'));
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closePaymentModal(); });
    </script>
<script>
document.addEventListener('DOMContentLoaded',function(){let t;document.querySelectorAll('form[method="GET"] input[type="text"]').forEach(function(i){i.addEventListener('input',function(){clearTimeout(t);t=setTimeout(function(){i.closest('form').submit()},400)})});document.querySelectorAll('form[method="GET"] select,form[method="GET"] input[type="date"]').forEach(function(el){el.addEventListener('change',function(){el.closest('form').submit()})})});
</script>
@endsection
