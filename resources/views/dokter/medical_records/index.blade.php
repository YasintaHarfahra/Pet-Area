@extends('layouts.dokter_app')

@section('title', 'Rekam Medis & Resep - Dokter')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Daftar Rekam Medis Pasien</h2>
                <p class="text-gray-500 mt-1 text-sm">Kelola rekam medis dan resep pasien</p>
            </div>
            <a href="{{ route('dokter.medical_records.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold flex items-center space-x-2 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Rekam Medis Baru</span>
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">
            <form method="GET" action="{{ route('dokter.medical_records.index') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">
                <div class="xl:col-span-2">
                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Cari Pasien / Dokter / Diagnosis</label>
                    <div class="mt-2 relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.85z"/>
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ $filters['search'] }}"
                               class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-100 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400"
                               placeholder="Nama pasien, nomor RM, dokter...">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal (dari)</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] }}"
                           class="mt-2 w-full rounded-xl border border-gray-100 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal (sampai)</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] }}"
                           class="mt-2 w-full rounded-xl border border-gray-100 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Dokter</label>
                    <select name="doctor_id"
                            class="mt-2 w-full rounded-xl border border-gray-100 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                        <option value="">Semua Dokter</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected($filters['doctor_id'] == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis Hewan</label>
                    <select name="species"
                            class="mt-2 w-full rounded-xl border border-gray-100 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                        <option value="">Semua Jenis</option>
                        @foreach ($speciesOptions as $species)
                            <option value="{{ $species }}" @selected($filters['species'] === $species)>{{ $species }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="xl:col-span-6 flex flex-wrap items-center gap-3 mt-2">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                        Terapkan Filter
                    </button>
                    @php
                        $hasFilters = collect($filters)->filter()->isNotEmpty();
                    @endphp
                    @if ($hasFilters)
                        <a href="{{ route('dokter.medical_records.index') }}"
                           class="text-xs font-semibold text-gray-500 hover:text-gray-700">Reset Filter</a>
                    @endif
                    <span class="text-xs text-gray-500 ml-auto">
                        {{ $filteredCount }} hasil ditemukan
                    </span>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto bg-white rounded-2xl border border-gray-100">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-3 text-left">Tanggal Kunjungan</th>
                        <th class="px-6 py-3 text-left">Nomor RM / Pasien</th>
                        <th class="px-6 py-3 text-left">Jenis Hewan</th>
                        <th class="px-6 py-3 text-left">Keluhan / Diagnosis</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                    @forelse ($medicalRecords as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-3">
                                <p class="font-semibold text-gray-900 text-xs">{{ $record->patient->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $record->patient->medical_record_number ?? 'NRM-' . $record->patient_id }}</p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="font-semibold text-xs">{{ $record->patient->species ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $record->patient->breed ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-gray-900 text-xs">{{ Str::limit($record->symptoms ?? '-', 40) }}</p>
                                <p class="text-xs text-gray-500">Diag: {{ Str::limit($record->diagnosis ?? '-', 40) }}</p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('medical_records.show', $record->id) }}" 
                                       class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                       title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @php
                                        $visitDate = $record->visit_date ? \Carbon\Carbon::parse($record->visit_date) : null;
                                        $editDeadline = $visitDate ? $visitDate->copy()->addHours(48) : null;
                                        $canEdit = $visitDate ? now()->lessThan($editDeadline) : false;
                                    @endphp
                                    @if($canEdit)
                                        <a href="{{ route('dokter.medical_records.edit', $record->id) }}" 
                                           class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors"
                                           title="Edit Rekam Medis">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('dokter.medical_records.destroy', $record->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Hapus Rekam Medis">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <div class="flex items-center justify-center gap-1 text-gray-400 text-xs font-semibold px-2 py-1.5 rounded-lg bg-gray-50 border border-dashed border-gray-200" title="Rekam medis terkunci karena melewati batas 2x24 jam">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            <span>Terkunci</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center space-x-3">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="font-semibold text-sm">Tidak ada rekam medis ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $medicalRecords->links('pagination::tailwind') }}
        </div>
    </div>
@endsection