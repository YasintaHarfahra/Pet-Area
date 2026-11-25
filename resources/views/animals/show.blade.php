{{-- 
=====================================================
VIEW: ANIMALS/SHOW.BLADE.PHP
=====================================================

Halaman DETAIL HEWAN yang menampilkan:
1. Info hewan lengkap
2. Link ke pemilik
3. Daftar REKAM MEDIS hewan ini (inilah hierarki level 3)

Ini menampilkan hierarki: Pemilik → [HEWAN INI] → Rekam Medis

Digunakan oleh: AnimalController@show
=====================================================
--}}

@extends('layouts.admin_app')

@section('title', 'Detail Hewan - ' . $animal->nama_hewan)
@section('header_title', 'Detail Hewan')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Breadcrumb Navigation --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('owners.index') }}" class="hover:text-slate-700">Pemilik</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('owners.show', $animal->owner_id) }}" class="hover:text-slate-700">{{ $animal->owner->nama }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-800 font-medium">{{ $animal->nama_hewan }}</span>
    </div>

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
    {{-- SECTION 1: INFORMASI HEWAN --}}
    {{-- ================================================ --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
        {{-- Header dengan Gradient --}}
        <div class="bg-gradient-to-r from-[#970747] to-purple-600 px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    {{-- Emoji Jenis --}}
                    <div class="w-20 h-20 rounded-xl bg-white/20 flex items-center justify-center text-5xl">
                        {{ $animal->species_emoji }}
                    </div>
                    <div>
                        <p class="text-pink-100 text-sm">{{ $animal->animal_code }}</p>
                        <h1 class="text-2xl font-bold text-white">{{ $animal->nama_hewan }}</h1>
                        <p class="text-pink-100">
                            {{ $animal->jenis }} {{ $animal->ras ? '• ' . $animal->ras : '' }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    @if(!$animal->is_active)
                        <span class="px-3 py-1 bg-slate-500/80 text-white rounded-full text-sm">Non-aktif</span>
                    @endif
                    <a href="{{ route('animals.edit', $animal->id) }}" 
                       class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg font-medium transition-colors">
                        ✏️ Edit
                    </a>
                </div>
            </div>
        </div>

        {{-- Link ke Pemilik --}}
        <div class="px-6 py-3 bg-pink-50 border-b border-pink-100">
            <a href="{{ route('owners.show', $animal->owner_id) }}" 
               class="inline-flex items-center gap-2 text-[#7a0538] hover:text-[#7a0538] font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Pemilik: {{ $animal->owner->nama }}
                <span class="text-[#970747] text-sm">({{ $animal->owner->no_hp }})</span>
            </a>
        </div>

        {{-- Detail Info --}}
        <div class="px-6 py-5">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                {{-- Jenis Kelamin --}}
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Jenis Kelamin</p>
                    <p class="font-semibold text-slate-800">
                        @if($animal->jenis_kelamin)
                            {{ $animal->jenis_kelamin == 'Jantan' ? '♂️' : '♀️' }} {{ $animal->jenis_kelamin }}
                        @else
                            -
                        @endif
                    </p>
                </div>
                
                {{-- Warna --}}
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Warna</p>
                    <p class="font-semibold text-slate-800">{{ $animal->warna ?? '-' }}</p>
                </div>
                
                {{-- Berat --}}
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Berat</p>
                    <p class="font-semibold text-slate-800">
                        {{ $animal->berat ? $animal->berat . ' kg' : '-' }}
                    </p>
                </div>
                
                {{-- Umur --}}
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Umur</p>
                    <p class="font-semibold text-slate-800">{{ $animal->calculated_age ?? '-' }}</p>
                </div>
            </div>

            {{-- Tanggal Lahir --}}
            @if($animal->tanggal_lahir)
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">📅 Tanggal Lahir</p>
                <p class="text-slate-700">{{ $animal->tanggal_lahir->format('d F Y') }}</p>
            </div>
            @endif

            {{-- Tanda Khusus --}}
            @if($animal->tanda_khusus)
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">🏷️ Tanda Khusus</p>
                <p class="text-slate-700">{{ $animal->tanda_khusus }}</p>
            </div>
            @endif

            {{-- Riwayat Alergi - PENTING untuk dokter --}}
            @if($animal->riwayat_alergi)
            <div class="mt-4 pt-4 border-t border-slate-100">
                <div class="flex items-start gap-2">
                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-medium">⚠️ ALERGI</span>
                    <p class="text-red-700 font-medium">{{ $animal->riwayat_alergi }}</p>
                </div>
            </div>
            @endif

            {{-- Catatan --}}
            @if($animal->catatan)
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">📝 Catatan</p>
                <p class="text-slate-600 text-sm">{{ $animal->catatan }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ================================================ --}}
    {{-- SECTION 2: DAFTAR REKAM MEDIS --}}
    {{-- Ini adalah level 3 dari hierarki --}}
    {{-- ================================================ --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                📋 Riwayat Rekam Medis
            </h2>
            
            {{-- Tombol Tambah Rekam Medis Baru --}}
            <a href="{{ route('animal-records.create', ['animal_id' => $animal->id]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#970747] hover:bg-[#7a0538] text-white rounded-lg text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Rekam Medis Baru
            </a>
        </div>

        {{-- Daftar Rekam Medis --}}
        <div class="p-6">
            @if($animal->medicalRecords->count() > 0)
                <div class="space-y-4">
                    @foreach($animal->medicalRecords->sortByDesc('tanggal_periksa') as $record)
                        {{-- Card Rekam Medis --}}
                        <a href="{{ route('animal-records.show', $record->id) }}" 
                           class="block p-4 rounded-xl border-2 border-slate-200 hover:border-pink-300 hover:bg-pink-50/30 transition-all group">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    {{-- Tanggal & Nomor RM --}}
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-lg font-bold text-slate-800">
                                            {{ $record->tanggal_periksa->format('d M Y') }}
                                        </span>
                                        <span class="text-sm text-slate-400 font-mono">{{ $record->nomor_rm }}</span>
                                        
                                        {{-- Status Badge --}}
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium 
                                                     {{ $record->status == 'selesai' ? 'bg-emerald-100 text-emerald-700' : 
                                                        ($record->status == 'dalam_perawatan' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                            {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                        </span>
                                    </div>
                                    
                                    {{-- Keluhan --}}
                                    <p class="text-slate-600 mb-2">
                                        <span class="font-medium">Keluhan:</span> {{ Str::limit($record->keluhan, 100) }}
                                    </p>
                                    
                                    {{-- Diagnosa --}}
                                    @if($record->diagnosa)
                                        <p class="text-slate-600 text-sm">
                                            <span class="font-medium">Diagnosa:</span> {{ Str::limit($record->diagnosa, 80) }}
                                        </p>
                                    @endif
                                    
                                    {{-- Info Dokter & Kontrol --}}
                                    <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                                        @if($record->doctor)
                                            <span>👨‍⚕️ {{ $record->doctor->name }}</span>
                                        @endif
                                        @if($record->tanggal_kontrol)
                                            <span class="{{ $record->tanggal_kontrol->isPast() ? 'text-red-500' : 'text-amber-500' }}">
                                                📅 Kontrol: {{ $record->tanggal_kontrol->format('d M Y') }}
                                            </span>
                                        @endif
                                        @if($record->total_biaya > 0)
                                            <span>💰 Rp {{ number_format($record->total_biaya, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Arrow --}}
                                <svg class="w-5 h-5 text-slate-300 group-hover:text-[#970747] transition-colors flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📋</div>
                    <p class="text-slate-500 mb-4">Belum ada rekam medis untuk {{ $animal->nama_hewan }}</p>
                    <a href="{{ route('animal-records.create', ['animal_id' => $animal->id]) }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-[#970747] hover:bg-[#7a0538] text-white rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Rekam Medis Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
