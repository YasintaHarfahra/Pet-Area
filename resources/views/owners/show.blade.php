{{-- 
=====================================================
VIEW: OWNERS/SHOW.BLADE.PHP
=====================================================

Halaman DETAIL PEMILIK yang menampilkan:
1. Informasi pemilik (nama, alamat, no HP)
2. Daftar hewan milik pemilik
3. Klik hewan untuk lihat rekam medis

Ini adalah halaman HIERARKI UTAMA dalam sistem berbasis pemilik.

Digunakan oleh: OwnerController@show
=====================================================
--}}

@extends('layouts.admin_app')

@section('title', 'Detail Pemilik - ' . $owner->nama)
@section('header_title', 'Detail Pemilik')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Tombol Kembali --}}
    <a href="{{ route('owners.index') }}" 
       class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 mb-6 group">
        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar
    </a>

    {{-- Alert Sukses --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ================================================ --}}
    {{-- SECTION 1: INFORMASI PEMILIK --}}
    {{-- ================================================ --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
        {{-- Header dengan Gradient --}}
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    {{-- Avatar --}}
                    <div class="w-16 h-16 rounded-xl bg-white/20 flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($owner->nama, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-emerald-100 text-sm">Kode: {{ $owner->owner_code }}</p>
                        <h1 class="text-2xl font-bold text-white">{{ $owner->nama }}</h1>
                    </div>
                </div>
                
                {{-- Tombol Edit --}}
                <a href="{{ route('owners.edit', $owner->id) }}" 
                   class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg font-medium transition-colors">
                    ✏️ Edit
                </a>
            </div>
        </div>

        {{-- Detail Info dalam Grid --}}
        <div class="px-6 py-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- No HP --}}
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">📱 No. Handphone</p>
                    <p class="font-semibold text-slate-800 text-lg font-mono">{{ $owner->no_hp }}</p>
                </div>
                
                {{-- Email --}}
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">📧 Email</p>
                    <p class="font-semibold text-slate-800">{{ $owner->email ?? '-' }}</p>
                </div>
                
                {{-- No KTP --}}
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">🪪 No. KTP</p>
                    <p class="font-semibold text-slate-800 font-mono">{{ $owner->no_ktp ?? '-' }}</p>
                </div>
            </div>
            
            {{-- Alamat --}}
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">📍 Alamat</p>
                <p class="text-slate-700">{{ $owner->alamat ?? 'Alamat belum diisi' }}</p>
            </div>
            
            {{-- Catatan --}}
            @if($owner->catatan)
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">📝 Catatan</p>
                <p class="text-slate-600 text-sm">{{ $owner->catatan }}</p>
            </div>
            @endif
        </div>

        {{-- Statistik --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-emerald-600">{{ $stats['total_hewan'] }}</p>
                    <p class="text-sm text-slate-500">Total Hewan</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-teal-600">{{ $stats['hewan_aktif'] }}</p>
                    <p class="text-sm text-slate-500">Hewan Aktif</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-[#970747]">{{ $stats['total_rekam_medis'] }}</p>
                    <p class="text-sm text-slate-500">Rekam Medis</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold {{ $stats['perlu_kontrol'] > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                        {{ $stats['perlu_kontrol'] }}
                    </p>
                    <p class="text-sm text-slate-500">Perlu Kontrol</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================ --}}
    {{-- SECTION 2: DAFTAR HEWAN --}}
    {{-- ================================================ --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                🐾 Daftar Hewan Peliharaan
            </h2>
            
            {{-- Tombol Tambah Hewan --}}
            <a href="{{ route('animals.create', ['owner_id' => $owner->id]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Hewan
            </a>
        </div>

        {{-- Daftar Hewan --}}
        <div class="p-6">
            @if($owner->animals->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($owner->animals as $animal)
                        {{-- Card Hewan --}}
                        <a href="{{ route('animals.show', $animal->id) }}" 
                           class="block p-4 rounded-xl border-2 
                                  {{ $animal->is_active ? 'border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/30' : 'border-slate-100 bg-slate-50 opacity-60' }} 
                                  transition-all group">
                            <div class="flex items-start gap-4">
                                {{-- Emoji Jenis --}}
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center flex-shrink-0 text-3xl">
                                    {{ $animal->species_emoji }}
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    {{-- Nama & Kode --}}
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-bold text-slate-800 text-lg group-hover:text-emerald-700 transition-colors">
                                            {{ $animal->nama_hewan }}
                                        </h3>
                                        @if(!$animal->is_active)
                                            <span class="px-2 py-0.5 bg-slate-200 text-slate-600 text-xs rounded-full">Non-aktif</span>
                                        @endif
                                    </div>
                                    
                                    <p class="text-sm text-slate-500 mb-2">
                                        {{ $animal->jenis }} {{ $animal->ras ? '• ' . $animal->ras : '' }}
                                    </p>
                                    
                                    {{-- Info Tambahan --}}
                                    <div class="flex flex-wrap gap-3 text-xs text-slate-400">
                                        <span>🏷️ {{ $animal->animal_code }}</span>
                                        @if($animal->jenis_kelamin)
                                            <span>{{ $animal->jenis_kelamin == 'Jantan' ? '♂️' : '♀️' }} {{ $animal->jenis_kelamin }}</span>
                                        @endif
                                        @if($animal->calculated_age)
                                            <span>📅 {{ $animal->calculated_age }}</span>
                                        @endif
                                    </div>
                                    
                                    {{-- Badge Rekam Medis --}}
                                    <div class="mt-3 flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-pink-100 text-[#7a0538] rounded-lg text-xs font-medium">
                                            📋 {{ $animal->medical_records_count }} Rekam Medis
                                        </span>
                                        
                                        @if($animal->latestMedicalRecord)
                                            <span class="text-xs text-slate-400">
                                                Terakhir: {{ $animal->latestMedicalRecord->tanggal_periksa->format('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Arrow --}}
                                <svg class="w-5 h-5 text-slate-300 group-hover:text-emerald-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🐾</div>
                    <p class="text-slate-500 mb-4">Pemilik ini belum memiliki hewan terdaftar</p>
                    <a href="{{ route('animals.create', ['owner_id' => $owner->id]) }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Daftarkan Hewan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
