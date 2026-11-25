{{-- 
=====================================================
VIEW: ANIMAL-RECORDS/INDEX.BLADE.PHP
=====================================================

Halaman daftar SEMUA REKAM MEDIS dari semua hewan.
Bisa difilter berdasarkan status, tanggal, atau pencarian.
Biasanya digunakan oleh dokter/admin untuk melihat overview.

Untuk melihat rekam medis per hewan, gunakan animals/show.blade.php

Digunakan oleh: AnimalMedicalRecordController@index
=====================================================
--}}

@extends('layouts.admin_app')

@section('title', 'Rekam Medis Hewan')
@section('header_title', 'Rekam Medis Hewan')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Statistik Cepat --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-slate-500 to-slate-600 rounded-xl p-4 text-white">
            <p class="text-slate-200 text-sm">Total Rekam Medis</p>
            <p class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl p-4 text-white">
            <p class="text-amber-100 text-sm">Dalam Perawatan</p>
            <p class="text-3xl font-bold">{{ $stats['dalam_perawatan'] ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl p-4 text-white">
            <p class="text-emerald-100 text-sm">Selesai</p>
            <p class="text-3xl font-bold">{{ $stats['selesai'] ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl p-4 text-white">
            <p class="text-rose-100 text-sm">Perlu Kontrol</p>
            <p class="text-3xl font-bold">{{ $stats['perlu_kontrol'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
        <div class="px-6 py-4">
            <form action="{{ route('animal-records.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                {{-- Search --}}
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="🔍 Cari nomor RM, nama hewan, atau pemilik..."
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                </div>
                
                {{-- Filter Status --}}
                <select name="status" 
                        class="px-4 py-2.5 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                    <option value="">Semua Status</option>
                    <option value="pemeriksaan" {{ request('status') == 'pemeriksaan' ? 'selected' : '' }}>Pemeriksaan</option>
                    <option value="dalam_perawatan" {{ request('status') == 'dalam_perawatan' ? 'selected' : '' }}>Dalam Perawatan</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>

                {{-- Filter Tanggal --}}
                <input type="date" 
                       name="tanggal" 
                       value="{{ request('tanggal') }}"
                       class="px-4 py-2.5 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                
                <button type="submit" 
                        class="px-6 py-2.5 bg-[#970747] hover:bg-[#7a0538] text-white rounded-xl font-medium transition-colors">
                    Cari
                </button>
                
                @if(request()->hasAny(['search', 'status', 'tanggal']))
                    <a href="{{ route('animal-records.index') }}" class="text-slate-600 hover:text-slate-800 font-medium">
                        Reset
                    </a>
                @endif
            </form>
        </div>
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

    {{-- Tabel Rekam Medis --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800">📋 Daftar Rekam Medis</h2>
            <a href="{{ route('animal-records.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#970747] hover:bg-[#7a0538] text-white rounded-lg text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Rekam Medis Baru
            </a>
        </div>

        @if($records->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">No. RM</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Hewan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Pemilik</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Keluhan</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($records as $record)
                            <tr class="hover:bg-slate-50 transition-colors">
                                {{-- Tanggal --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="font-semibold text-slate-800">{{ $record->tanggal_periksa->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-400">{{ $record->tanggal_periksa->format('H:i') }}</p>
                                </td>
                                
                                {{-- No RM --}}
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm text-slate-600">{{ $record->nomor_rm }}</span>
                                </td>
                                
                                {{-- Hewan --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl">{{ $record->animal->species_emoji ?? '🐾' }}</span>
                                        <div>
                                            <a href="{{ route('animals.show', $record->animal_id) }}" 
                                               class="font-medium text-[#970747] hover:text-[#7a0538] hover:underline">
                                                {{ $record->animal->nama_hewan }}
                                            </a>
                                            <p class="text-xs text-slate-400">{{ $record->animal->jenis }}</p>
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- Pemilik --}}
                                <td class="px-6 py-4">
                                    <a href="{{ route('owners.show', $record->animal->owner_id) }}" 
                                       class="text-emerald-600 hover:text-emerald-700 hover:underline">
                                        {{ $record->animal->owner->nama }}
                                    </a>
                                </td>
                                
                                {{-- Keluhan --}}
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-600 max-w-[200px] truncate">
                                        {{ $record->keluhan }}
                                    </p>
                                </td>
                                
                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                                                 {{ $record->status == 'selesai' ? 'bg-emerald-100 text-emerald-700' : 
                                                    ($record->status == 'dalam_perawatan' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                        {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                    </span>
                                    
                                    @if($record->tanggal_kontrol && $record->tanggal_kontrol->isPast() && $record->status != 'selesai')
                                        <p class="text-xs text-red-500 mt-1">⚠️ Kontrol terlewat</p>
                                    @endif
                                </td>
                                
                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('animal-records.show', $record->id) }}" 
                                           class="p-2 text-slate-400 hover:text-[#970747] hover:bg-pink-50 rounded-lg transition-colors"
                                           title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('animal-records.edit', $record->id) }}" 
                                           class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                           title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($records->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $records->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📋</div>
                <p class="text-slate-500 mb-4">Belum ada rekam medis</p>
                <a href="{{ route('animal-records.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-[#970747] hover:bg-[#7a0538] text-white rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Rekam Medis
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
