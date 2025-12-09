@extends('layouts.admin_app')

@section('title', 'Lihat Rekam Medis - Admin')
@section('header_title', 'Daftar Rekam Medis')

@section('content')
    <div class="flex flex-col gap-6">
        <div>
            <h2 class="text-3xl font-semibold text-gray-800">Daftar Rekam Medis</h2>
            <p class="text-gray-500 mt-1">Gunakan filter di bawah untuk menemukan rekam medis yang Anda butuhkan.</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">
            <form method="GET" action="{{ route('admin.medical_records.index') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">
                <div class="xl:col-span-2">
                    <label class="text-sm font-semibold text-gray-700">Cari Pasien / Dokter / Diagnosis</label>
                    <div class="mt-2 relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.85z"/>
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ $filters['search'] }}"
                               class="w-full pl-10 rounded-xl border-gray-200 focus:ring-2 focus:ring-purple-200 focus:border-purple-400"
                               placeholder="Nama pasien, nomor RM, dokter, diagnosis...">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Rentang Tanggal (dari)</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] }}"
                           class="mt-2 w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-purple-200 focus:border-purple-400">
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Rentang Tanggal (sampai)</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] }}"
                           class="mt-2 w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-purple-200 focus:border-purple-400">
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Dokter Penanggung Jawab</label>
                    <select name="doctor_id"
                            class="mt-2 w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-purple-200 focus:border-purple-400">
                        <option value="">Semua Dokter</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected($filters['doctor_id'] == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Jenis Hewan</label>
                    <select name="species"
                            class="mt-2 w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-purple-200 focus:border-purple-400">
                        <option value="">Semua Jenis</option>
                        @foreach ($speciesOptions as $species)
                            <option value="{{ $species }}" @selected($filters['species'] === $species)>{{ $species }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="xl:col-span-6 flex flex-wrap items-center gap-3 mt-2">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 rounded-xl bg-purple-600 text-white font-semibold hover:bg-purple-700 transition-colors">
                        Terapkan Filter
                    </button>
                    @php
                        $hasFilters = collect($filters)->filter()->isNotEmpty();
                    @endphp
                    @if ($hasFilters)
                        <a href="{{ route('admin.medical_records.index') }}"
                           class="text-sm font-semibold text-gray-500 hover:text-gray-700">Reset Filter</a>
                    @endif
                    <span class="text-sm text-gray-500 ml-auto">
                        {{ $filteredCount }} hasil ditemukan
                    </span>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto bg-white rounded-2xl border border-gray-100">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Tanggal Kunjungan</th>
                        <th class="px-6 py-3 text-left">Nomor RM / Pasien</th>
                        <th class="px-6 py-3 text-left">Jenis Hewan</th>
                        <th class="px-6 py-3 text-left">Dokter</th>
                        <th class="px-6 py-3 text-left">Keluhan / Diagnosis</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse ($medicalRecords as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $record->patient->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $record->patient->medical_record_number ?? 'NRM-' . $record->patient_id }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold">{{ $record->patient->species ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $record->patient->breed ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $record->doctor->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">({{ $record->doctor->role ?? 'Dokter' }})</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-900">{{ Str::limit($record->symptoms ?? '-', 40) }}</p>
                                <p class="text-xs text-gray-500">Diag: {{ Str::limit($record->diagnosis ?? '-', 40) }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('medical_records.show', $record->id) }}"
                                   class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800">
                                    Lihat Detail
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data rekam medis yang sesuai dengan filter.
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