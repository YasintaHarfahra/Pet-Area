@extends('layouts.admin_app')

@section('title', 'Lihat Rekam Medis - Admin')
@section('header_title', 'Daftar Rekam Medis')

@section('content')
    <div class="max-w-7xl mx-auto px-4 pb-12">
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-heading-1 mb-2">Daftar Rekam Medis</h1>
            <p class="text-gray-600">Kelola dan pantau rekam medis seluruh pasien dengan mudah</p>
        </div>

        {{-- Filter Section --}}
        <div class="card-elevated p-6 mb-8">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Filter & Pencarian</h3>
            </div>
            <form method="GET" action="{{ route('admin.medical_records.index') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">
                <div class="xl:col-span-2">
                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2 block">Cari Pasien / RM / Dokter</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.85z"/>
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ $filters['search'] }}"
                               class="input-base w-full pl-10 pr-4"
                               placeholder="Cari...">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2 block">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] }}"
                           class="input-base w-full">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2 block">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] }}"
                           class="input-base w-full">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2 block">Dokter</label>
                    <select name="doctor_id"
                            class="input-base w-full">
                        <option value="">Semua Dokter</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id_user }}" @selected($filters['doctor_id'] == $doctor->id_user)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2 block">Jenis Hewan</label>
                    <select name="species"
                            class="input-base w-full">
                        <option value="">Semua Jenis</option>
                        @foreach ($speciesOptions as $species)
                            <option value="{{ $species }}" @selected($filters['species'] === $species)>{{ $species }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="xl:col-span-6 flex flex-wrap items-center gap-3 pt-2">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2.5 rounded-lg bg-gradient-to-r from-[#970747] to-[#970747] text-white text-sm font-semibold hover:from-[#7a0538] hover:to-[#7a0538] transition-all shadow-sm hover:shadow-md-enhanced">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Terapkan Filter
                    </button>
                    @php
                        $hasFilters = collect($filters)->filter()->isNotEmpty();
                    @endphp
                    @if ($hasFilters)
                        <a href="{{ route('admin.medical_records.index') }}"
                           class="text-xs font-semibold text-gray-500 hover:text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-lg transition">↻ Reset Filter</a>
                    @endif
                    <span class="text-xs text-gray-500 ml-auto font-medium px-3 py-2 bg-gray-100 rounded-lg">
                        📊 {{ $filteredCount }} hasil ditemukan
                    </span>
                </div>
            </form>
        </div>

        {{-- Table Section --}}
        <div class="card-premium overflow-hidden">
            @if ($medicalRecords->count() > 0)
                <div class="w-full overflow-x-hidden">
                    <table class="w-full divide-y divide-gray-100">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-4 sm:px-6 py-4 text-left">
                                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal</span>
                                </th>
                                <th class="px-4 sm:px-6 py-4 text-left">
                                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Pasien</span>
                                </th>
                                <th class="px-4 sm:px-6 py-4 text-left hidden lg:table-cell">
                                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Jenis Hewan</span>
                                </th>
                                <th class="px-4 sm:px-6 py-4 text-left hidden md:table-cell">
                                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Dokter</span>
                                </th>
                                <th class="px-4 sm:px-6 py-4 text-left">
                                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Keluhan</span>
                                </th>
                                <th class="px-4 sm:px-6 py-4 text-center">
                                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            @forelse ($medicalRecords as $record)
                                <tr class="hover:bg-pink-50 transition-all duration-200 table-row-hover">
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs px-2.5 py-1 rounded-lg bg-pink-100 text-[#7a0538] font-medium whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M') }}
                                            </span>
                                            <span class="text-xs text-gray-500 hidden sm:inline-block">{{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center text-[#970747] font-bold text-xs sm:text-sm flex-shrink-0">
                                                {{ substr($record->patient->name, 0, 1) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="font-semibold text-gray-900 truncate">{{ $record->patient->name ?? '-' }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ $record->patient->medical_record_number ?? 'NRM-' . $record->id_pet }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 hidden lg:table-cell">
                                        <div>
                                            <p class="font-semibold text-gray-900 truncate">{{ $record->patient->species ?? '-' }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $record->patient->breed ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 hidden md:table-cell">
                                        <div>
                                            <p class="font-semibold text-gray-900 truncate">{{ $record->doctor->name ?? '-' }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $record->doctor->role ?? 'Dokter' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 min-w-0">
                                        <div class="max-w-xs">
                                            <p class="text-gray-900 text-xs sm:text-sm break-words"><strong>Keluhan:</strong> {{ Str::limit($record->symptoms ?? '-', 30, '...') }}</p>
                                            <p class="text-xs text-gray-600 mt-1 hidden sm:block break-words"><strong>Diagnosis:</strong> {{ Str::limit($record->diagnosis ?? '-', 30, '...') }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-center">
                                        <a href="{{ route('medical_records.show', $record->id_medical_record) }}"
                                           class="inline-flex items-center px-3 py-2 text-[#970747] font-semibold hover:text-[#7a0538] hover:bg-pink-50 rounded-lg transition-all shadow-sm hover:shadow-md-enhanced text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16">
                                        <div class="flex flex-col items-center justify-center gap-4 text-gray-500">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div class="text-center">
                                                <p class="font-semibold text-base mb-1">Tidak ada data rekam medis</p>
                                                <p class="text-sm text-gray-400">Tidak ada data yang sesuai dengan filter Anda</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-16">
                    <div class="flex flex-col items-center justify-center gap-4 text-gray-500">
                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div class="text-center">
                            <p class="font-semibold text-lg mb-2">Belum ada rekam medis</p>
                            <p class="text-sm text-gray-400">Tidak ada data rekam medis tersedia</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Pagination --}}
        @if ($medicalRecords->count() > 0)
            <div class="mt-8">
                {{ $medicalRecords->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

<script>
document.addEventListener('DOMContentLoaded',function(){let t;document.querySelectorAll('form[method="GET"] input[type="text"]').forEach(function(i){i.addEventListener('input',function(){clearTimeout(t);t=setTimeout(function(){i.closest('form').submit()},400)})});document.querySelectorAll('form[method="GET"] select,form[method="GET"] input[type="date"]').forEach(function(el){el.addEventListener('change',function(){el.closest('form').submit()})})});
</script>
@endsection