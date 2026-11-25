{{-- 
=====================================================
VIEW: ANIMAL-RECORDS/SHOW.BLADE.PHP
=====================================================

Halaman DETAIL REKAM MEDIS yang menampilkan:
- Info hewan & pemilik
- Semua data pemeriksaan
- Diagnosa, tindakan, obat
- Vital signs
- Biaya
- Status & kontrol

Digunakan oleh: AnimalMedicalRecordController@show
=====================================================
--}}

@extends('layouts.admin_app')

@section('title', 'Detail Rekam Medis')
@section('header_title', 'Detail Rekam Medis')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('owners.show', $record->animal->owner_id) }}" class="hover:text-slate-700">
            {{ $record->animal->owner->nama }}
        </a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('animals.show', $record->animal_id) }}" class="hover:text-slate-700">
            {{ $record->animal->nama_hewan }}
        </a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-800 font-medium">{{ $record->nomor_rm }}</span>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ================================ --}}
    {{-- HEADER CARD --}}
    {{-- ================================ --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-[#970747] to-purple-600 px-6 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm font-mono">{{ $record->nomor_rm }}</p>
                    <h1 class="text-2xl font-bold text-white">
                        Rekam Medis - {{ $record->tanggal_periksa->format('d M Y') }}
                    </h1>
                </div>
                
                <div class="flex items-center gap-2">
                    {{-- Status Badge --}}
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                                 {{ $record->status == 'selesai' ? 'bg-emerald-500/80 text-white' : 
                                    ($record->status == 'dalam_perawatan' ? 'bg-amber-500/80 text-white' : 'bg-white/20 text-white') }}">
                        {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                    </span>
                    
                    <a href="{{ route('animal-records.edit', $record->id) }}" 
                       class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg font-medium transition-colors">
                        ✏️ Edit
                    </a>
                </div>
            </div>
        </div>

        {{-- Info Hewan & Pemilik --}}
        <div class="px-6 py-4 bg-pink-50 border-b border-pink-100 flex items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center text-2xl">
                    {{ $record->animal->species_emoji }}
                </div>
                <div>
                    <a href="{{ route('animals.show', $record->animal_id) }}" 
                       class="font-bold text-[#7a0538] hover:underline">
                        {{ $record->animal->nama_hewan }}
                    </a>
                    <p class="text-sm text-slate-500">{{ $record->animal->jenis }} {{ $record->animal->ras ? '• ' . $record->animal->ras : '' }}</p>
                </div>
            </div>
            
            <div class="h-10 w-px bg-pink-200"></div>
            
            <div>
                <p class="text-xs text-slate-400">Pemilik:</p>
                <a href="{{ route('owners.show', $record->animal->owner_id) }}" 
                   class="font-medium text-emerald-600 hover:underline">
                    {{ $record->animal->owner->nama }}
                </a>
                <span class="text-sm text-slate-400 ml-2">📱 {{ $record->animal->owner->no_hp }}</span>
            </div>
            
            @if($record->doctor)
                <div class="h-10 w-px bg-pink-200"></div>
                <div>
                    <p class="text-xs text-slate-400">Dokter:</p>
                    <p class="font-medium text-slate-700">👨‍⚕️ {{ $record->doctor->name }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ================================ --}}
    {{-- KELUHAN & PEMERIKSAAN --}}
    {{-- ================================ --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-800">📝 Keluhan & Pemeriksaan</h2>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Keluhan</p>
                <p class="text-slate-700">{{ $record->keluhan }}</p>
            </div>
            
            @if($record->hasil_pemeriksaan)
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Hasil Pemeriksaan</p>
                    <p class="text-slate-700">{{ $record->hasil_pemeriksaan }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ================================ --}}
    {{-- VITAL SIGNS --}}
    {{-- ================================ --}}
    @if($record->suhu_tubuh || $record->berat_badan || $record->detak_jantung || $record->respirasi)
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">💓 Tanda Vital</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 rounded-xl bg-rose-50">
                        <p class="text-xs text-rose-400 uppercase mb-1">Suhu Tubuh</p>
                        <p class="text-2xl font-bold text-rose-600">
                            {{ $record->suhu_tubuh ? $record->suhu_tubuh . '°C' : '-' }}
                        </p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-pink-50">
                        <p class="text-xs text-[#970747] uppercase mb-1">Berat Badan</p>
                        <p class="text-2xl font-bold text-[#970747]">
                            {{ $record->berat_badan ? $record->berat_badan . ' kg' : '-' }}
                        </p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-pink-50">
                        <p class="text-xs text-pink-400 uppercase mb-1">Detak Jantung</p>
                        <p class="text-2xl font-bold text-pink-600">
                            {{ $record->detak_jantung ?? '-' }}
                        </p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-teal-50">
                        <p class="text-xs text-teal-400 uppercase mb-1">Respirasi</p>
                        <p class="text-2xl font-bold text-teal-600">
                            {{ $record->respirasi ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ================================ --}}
    {{-- DIAGNOSA & TINDAKAN --}}
    {{-- ================================ --}}
    @if($record->diagnosa || $record->tindakan || $record->obat)
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">🩺 Diagnosa & Tindakan</h2>
            </div>
            <div class="p-6 space-y-4">
                @if($record->diagnosa)
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Diagnosa</p>
                        <p class="text-slate-700 font-medium">{{ $record->diagnosa }}</p>
                    </div>
                @endif
                
                @if($record->tindakan)
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Tindakan</p>
                        <p class="text-slate-700">{{ $record->tindakan }}</p>
                    </div>
                @endif
                
                @if($record->obat)
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">💊 Obat / Resep</p>
                        <div class="p-3 rounded-lg bg-amber-50 border border-amber-200">
                            <p class="text-slate-700 whitespace-pre-line">{{ $record->obat }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ================================ --}}
    {{-- BIAYA --}}
    {{-- ================================ --}}
    @if($record->biaya_pemeriksaan || $record->biaya_obat || $record->biaya_tindakan)
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">💰 Rincian Biaya</h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @if($record->biaya_pemeriksaan)
                        <div class="flex justify-between">
                            <span class="text-slate-600">Biaya Pemeriksaan</span>
                            <span class="font-medium">Rp {{ number_format($record->biaya_pemeriksaan, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($record->biaya_obat)
                        <div class="flex justify-between">
                            <span class="text-slate-600">Biaya Obat</span>
                            <span class="font-medium">Rp {{ number_format($record->biaya_obat, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($record->biaya_tindakan)
                        <div class="flex justify-between">
                            <span class="text-slate-600">Biaya Tindakan</span>
                            <span class="font-medium">Rp {{ number_format($record->biaya_tindakan, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between pt-3 border-t border-slate-200">
                        <span class="text-lg font-bold text-slate-800">Total</span>
                        <span class="text-lg font-bold text-emerald-600">
                            Rp {{ number_format($record->total_biaya, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ================================ --}}
    {{-- KONTROL & AKSI --}}
    {{-- ================================ --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex items-center justify-between">
                {{-- Info Kontrol --}}
                <div>
                    @if($record->tanggal_kontrol)
                        <p class="text-sm text-slate-500">📅 Jadwal Kontrol:</p>
                        <p class="text-lg font-semibold {{ $record->tanggal_kontrol->isPast() && $record->status != 'selesai' ? 'text-red-600' : 'text-slate-800' }}">
                            {{ $record->tanggal_kontrol->format('d F Y') }}
                            @if($record->tanggal_kontrol->isPast() && $record->status != 'selesai')
                                <span class="text-sm text-red-500">(Terlewat!)</span>
                            @endif
                        </p>
                    @else
                        <p class="text-slate-400">Tidak ada jadwal kontrol</p>
                    @endif
                </div>
                
                {{-- Tombol Aksi --}}
                <div class="flex items-center gap-3">
                    @if($record->status != 'selesai')
                        <form action="{{ route('animal-records.complete', $record->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    onclick="return confirm('Tandai rekam medis ini sebagai selesai?')"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors">
                                ✓ Selesai
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('animal-records.create', ['animal_id' => $record->animal_id]) }}" 
                       class="px-4 py-2 bg-[#970747] hover:bg-[#7a0538] text-white rounded-lg font-medium transition-colors">
                        + Rekam Medis Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-red-50 rounded-2xl border border-red-200 overflow-hidden">
        <div class="px-6 py-4">
            <h3 class="font-bold text-red-700 mb-2">⚠️ Zona Berbahaya</h3>
            <form action="{{ route('animal-records.destroy', $record->id) }}" 
                  method="POST" 
                  onsubmit="return confirm('Hapus rekam medis ini? Tindakan tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
                    🗑️ Hapus Rekam Medis
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
