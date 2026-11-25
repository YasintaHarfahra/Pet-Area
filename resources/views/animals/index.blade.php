{{-- 
=====================================================
VIEW: ANIMALS/INDEX.BLADE.PHP
=====================================================

Halaman daftar SEMUA HEWAN dari semua pemilik.
Bisa difilter berdasarkan jenis hewan atau pencarian.
Biasanya diakses dari menu utama untuk melihat semua hewan.

Untuk melihat hewan per pemilik, gunakan owners/show.blade.php

Digunakan oleh: AnimalController@index
=====================================================
--}}

@extends('layouts.admin_app')

@section('title', 'Daftar Hewan')
@section('header_title', 'Daftar Hewan')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Header dengan Statistik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl p-4 text-white">
            <p class="text-emerald-100 text-sm">Total Hewan</p>
            <p class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl p-4 text-white">
            <p class="text-amber-100 text-sm">🐕 Anjing</p>
            <p class="text-3xl font-bold">{{ $stats['anjing'] ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-br from-[#970747] to-purple-600 rounded-xl p-4 text-white">
            <p class="text-pink-100 text-sm">🐈 Kucing</p>
            <p class="text-3xl font-bold">{{ $stats['kucing'] ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl p-4 text-white">
            <p class="text-pink-100 text-sm">🐾 Lainnya</p>
            <p class="text-3xl font-bold">{{ $stats['lainnya'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Filter & Search Card --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
        <div class="px-6 py-4">
            <form action="{{ route('animals.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                {{-- Search Input --}}
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="🔍 Cari nama hewan atau nama pemilik..."
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all">
                </div>
                
                {{-- Filter Jenis --}}
                <select name="jenis" 
                        class="px-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all min-w-[150px]">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisOptions as $jenis)
                        <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                    @endforeach
                </select>

                {{-- Filter Status --}}
                <select name="status" 
                        class="px-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                </select>
                
                {{-- Tombol --}}
                <button type="submit" 
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium transition-colors">
                    Cari
                </button>
                
                @if(request()->hasAny(['search', 'jenis', 'status']))
                    <a href="{{ route('animals.index') }}" 
                       class="px-4 py-2.5 text-slate-600 hover:text-slate-800 font-medium">
                        Reset
                    </a>
                @endif
            </form>
        </div>
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

    {{-- Tabel Hewan --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800">🐾 Daftar Hewan</h2>
            <a href="{{ route('animals.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Hewan
            </a>
        </div>

        @if($animals->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Hewan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Pemilik</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Jenis/Ras</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Rekam Medis</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($animals as $animal)
                            <tr class="hover:bg-slate-50 transition-colors {{ !$animal->is_active ? 'opacity-50' : '' }}">
                                {{-- Info Hewan --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center text-2xl">
                                            {{ $animal->species_emoji }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $animal->nama_hewan }}</p>
                                            <p class="text-xs text-slate-400 font-mono">{{ $animal->animal_code }}</p>
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- Pemilik --}}
                                <td class="px-6 py-4">
                                    <a href="{{ route('owners.show', $animal->owner_id) }}" 
                                       class="text-emerald-600 hover:text-emerald-700 font-medium hover:underline">
                                        {{ $animal->owner->nama }}
                                    </a>
                                    <p class="text-xs text-slate-400">📱 {{ $animal->owner->no_hp }}</p>
                                </td>
                                
                                {{-- Jenis/Ras --}}
                                <td class="px-6 py-4">
                                    <p class="text-slate-700">{{ $animal->jenis }}</p>
                                    @if($animal->ras)
                                        <p class="text-xs text-slate-400">{{ $animal->ras }}</p>
                                    @endif
                                </td>
                                
                                {{-- Jumlah Rekam Medis --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium 
                                                 {{ $animal->medical_records_count > 0 ? 'bg-pink-100 text-[#7a0538]' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $animal->medical_records_count }}
                                    </span>
                                </td>
                                
                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    @if($animal->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                            ✓ Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                            Non-aktif
                                        </span>
                                    @endif
                                </td>
                                
                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('animals.show', $animal->id) }}" 
                                           class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                           title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('animals.edit', $animal->id) }}" 
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
            @if($animals->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $animals->withQueryString()->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="text-center py-16">
                <div class="text-6xl mb-4">🐾</div>
                <p class="text-slate-500 mb-4">Belum ada hewan terdaftar</p>
                <a href="{{ route('animals.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Hewan Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
