@extends('layouts.admin_app')

@section('title', 'Dashboard Admin - Pet Area Clinic')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header & Welcome --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-500">Selamat datang kembali, <span class="text-[#970747] font-semibold">{{ Auth::guard('admin')->user()->name }}</span></p>
    </div>

    {{-- ALERT SUCCESS/ERROR --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-sm">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- QUICK ACTIONS --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#970747]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Aksi Cepat
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            
            {{-- TOMBOL REMINDER WA (REVISI) --}}
            @if($sudahKirimHariIni)
                <div class="flex flex-col items-center p-5 bg-gray-100 border border-gray-300 rounded-xl text-gray-400 cursor-not-allowed opacity-80">
                    <div class="w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-center uppercase tracking-tighter">Sudah Dikirim</span>
                </div>
            @else
                <form action="{{ route('admin.reminder.kirim') }}" method="POST" class="h-full">
                    @csrf
                    <button type="submit" onclick="return confirm('Kirim pengingat WhatsApp ke semua pemilik hewan yang jadwal kontrolnya besok?')"
                        class="w-full h-full flex flex-col items-center p-5 bg-emerald-500 rounded-xl hover:bg-emerald-600 hover:shadow-lg transition-all text-white group border-2 border-emerald-700">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3 group-hover:bg-white/30 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-center">Kirim Reminder</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.registration.owners.create') }}" 
               class="flex flex-col items-center p-5 bg-[#970747] rounded-xl hover:bg-[#7a0538] hover:shadow-lg transition-all text-white group">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3 group-hover:bg-white/30 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-center">Tambah Pemilik</span>
            </a>

            <a href="{{ route('admin.registration.owners.index') }}" 
               class="flex flex-col items-center p-5 bg-green-600 rounded-xl hover:bg-green-700 hover:shadow-lg transition-all text-white group">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3 group-hover:bg-white/30 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-center">Data Pasien</span>
            </a>

            <a href="{{ route('admin.cashier.index') }}" 
               class="flex flex-col items-center p-5 bg-orange-500 rounded-xl hover:bg-orange-600 hover:shadow-lg transition-all text-white group">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3 group-hover:bg-white/30 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-center">Kasir</span>
            </a>

            <a href="{{ route('admin.medicines.index') }}" 
               class="flex flex-col items-center p-5 bg-red-600 rounded-xl hover:bg-red-700 hover:shadow-lg transition-all text-white group">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3 group-hover:bg-white/30 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-center">Stok Obat</span>
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-5 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pemilik</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['total_pemilik']) }}</p>
                </div>
                <div class="w-11 h-11 bg-pink-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#970747]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-5 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Hewan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['total_hewan']) }}</p>
                </div>
                <div class="w-11 h-11 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
            </div>
        </div>



        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-5 hover:shadow-lg transition {{ $stats['obat_stok_rendah'] > 0 ? 'border-l-4 border-l-red-500' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Stok Rendah</p>
                    <p class="text-2xl font-bold {{ $stats['obat_stok_rendah'] > 0 ? 'text-red-600' : 'text-gray-800' }} mt-1">{{ number_format($stats['obat_stok_rendah']) }}</p>
                </div>
                <div class="w-11 h-11 {{ $stats['obat_stok_rendah'] > 0 ? 'bg-red-100' : 'bg-gray-100' }} rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 {{ $stats['obat_stok_rendah'] > 0 ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>


    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Transaksi Belum Bayar --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Transaksi Belum Bayar</h2>
                <a href="{{ route('admin.cashier.index') }}" class="text-sm text-[#970747] hover:text-[#7a0538] font-semibold">
                    Lihat Semua →
                </a>
            </div>
            <div class="p-6">
                @if($pendingTransactions->count() > 0)
                <div class="space-y-3">
                    @foreach($pendingTransactions as $transaction)
                    <a href="{{ route('admin.cashier.show', $transaction) }}" class="block p-4 bg-orange-50 rounded-xl hover:bg-orange-100 transition border border-orange-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $transaction->nomor_transaksi }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $transaction->medicalRecord->pet->nama_hewan ?? 'N/A' }} - 
                                    {{ $transaction->medicalRecord->pet->owner->nama ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-orange-700">Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-green-700">Aman!</p>
                    <p class="text-gray-500 text-sm">Tidak ada tunggakan pembayaran</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Registrasi Terbaru --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Registrasi Terbaru</h2>
                <a href="{{ route('admin.registration.owners.index') }}" class="text-sm text-[#970747] hover:text-[#7a0538] font-semibold">
                    Lihat Semua →
                </a>
            </div>
            <div class="p-6">
                @if($recentOwners->count() > 0)
                <div class="space-y-3">
                    @foreach($recentOwners as $owner)
                    <a href="{{ route('admin.registration.owners.show', $owner) }}" class="block p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#970747] rounded-xl flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($owner->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $owner->nama }}</p>
                                    <p class="text-sm text-gray-500">{{ $owner->no_hp }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-pink-100 text-[#970747] text-xs rounded-full font-semibold">
                                {{ $owner->pets_count }} hewan
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500">Belum ada registrasi terbaru</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Obat Stok Rendah --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">Obat Stok Rendah</h2>
            <a href="{{ route('admin.medicines.index') }}" class="text-sm text-[#970747] hover:text-[#7a0538] font-semibold">
                Kelola Obat →
            </a>
        </div>
        <div class="p-6">
            @if($lowStockMedicines->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($lowStockMedicines as $medicine)
                <div class="p-4 rounded-xl border {{ $medicine->stok <= 0 ? 'bg-red-50 border-red-300' : 'bg-orange-50 border-orange-300' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $medicine->nama_obat }}</p>
                            <p class="text-sm text-gray-500">{{ $medicine->satuan }}</p>
                        </div>
                        <div class="text-right">
                            @if($medicine->stok <= 0)
                                <span class="px-3 py-1 bg-red-600 text-white text-sm font-bold rounded-full">HABIS</span>
                            @else
                                <span class="px-3 py-1 bg-orange-600 text-white text-sm font-bold rounded-full">Sisa: {{ $medicine->stok }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-gray-500">Semua stok obat dalam kondisi aman.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- TABEL RIWAYAT LOG WA (REVISI LENGKAP) --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <h2 class="text-lg font-bold text-gray-800">Log Pengiriman WA</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Nama Pemilik</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Nama Hewan</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Nomor WA</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($waLogs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $log->nama_pemilik }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $log->nama_hewan ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $log->nomor_wa }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-full uppercase">
                                    {{ $log->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Belum ada log WhatsApp hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection