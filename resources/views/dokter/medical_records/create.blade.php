@extends('layouts.dokter_app')

@section('title', 'Tambah Rekam Medis - Dokter')

@section('content')
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold gradient-text mb-2">Tambah Rekam Medis Baru</h1>
                <p class="text-gray-600">Catat rekam medis dan resep untuk pasien</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-6">
        {{-- Patient summary --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            @if(isset($patient))
                <input type="hidden" name="patient_id" form="medical-record-form" value="{{ $patient->id }}">
                @if(isset($queue_id))
                    <input type="hidden" name="queue_id" form="medical-record-form" value="{{ $queue_id }}">
                @endif
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-gray-100 p-4 flex flex-col gap-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Nama Pasien</p>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $patient->name }}</h2>
                                <p class="text-sm text-gray-500">No. RM: <span class="font-semibold text-gray-900">{{ $patient->medical_record_number ?? '-' }}</span></p>
                            </div>
                            <a href="{{ route('patients.show', $patient->id) }}" class="text-xs font-semibold text-blue-600 whitespace-nowrap">
                                Detail pasien →
                            </a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Pemilik</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $patient->owner_name }}</p>
                                <p class="text-xs text-gray-500">{{ $patient->owner_phone ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $patient->owner_email ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Alamat</p>
                                <p class="text-sm text-gray-900">{{ $patient->owner_address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-100 p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Jenis / Ras</p>
                            <p class="text-sm text-gray-900">{{ $patient->species ?? '-' }} / {{ $patient->breed ?? '-' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Berat: {{ $patient->weight ? $patient->weight . ' kg' : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Umur</p>
                            <p class="text-sm text-gray-900">
                                @if($patient->date_of_birth)
                                    {{ \Carbon\Carbon::parse($patient->date_of_birth)->translatedFormat('d M Y') }}<br>
                                    @php
                                        $dob = \Carbon\Carbon::parse($patient->date_of_birth);
                                        $diff = $dob->diff(\Carbon\Carbon::now());
                                    @endphp
                                    <span class="text-xs text-gray-500">{{ $diff->y }} tahun {{ $diff->m }} bulan</span>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="sm:col-span-2">
                            @php
                                $latestRecord = $patient->medicalRecords->sortByDesc('visit_date')->first();
                            @endphp
                            <p class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Keluhan Hari Ini</p>
                            <p class="text-sm text-gray-900">
                                {{ $latestRecord && $latestRecord->symptoms ? $latestRecord->symptoms : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="patient_id" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Pasien <span class="text-red-500">*</span></label>
                        <select id="patient_id" name="patient_id" form="medical-record-form" required
                                class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-purple-200 focus:border-purple-400">
                            <option value="">-- Pilih Pasien --</option>
                            @foreach ($patients as $patientOption)
                                <option value="{{ $patientOption->id }}" {{ old('patient_id') == $patientOption->id ? 'selected' : '' }}>
                                    {{ $patientOption->name }} (Pemilik: {{ $patientOption->owner_name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rounded-xl border border-dashed border-gray-200 p-4 bg-gray-50 text-sm text-gray-500 flex items-center">
                        Pilih pasien terlebih dahulu untuk menampilkan identitas dan riwayat pemeriksaan.
                    </div>
                </div>
            @endif
        </div>

        @if(isset($patient))
            {{-- History Section --}}
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Pemeriksaan</h3>
                        <p class="text-sm text-gray-500">Daftar pemeriksaan sebelumnya untuk referensi diagnosa.</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-sm text-gray-500 text-right">
                            Tanggal pemeriksaan hari ini:<br>
                            <span class="font-semibold text-gray-900">{{ now()->translatedFormat('d M Y') }}</span>
                        </div>
                        <button id="toggleFormButton"
                                class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Pemeriksaan
                        </button>
                    </div>
                </div>
                <div class="max-h-72 overflow-y-auto">
                    <table class="min-w-full text-xs text-gray-700 divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Keluhan</th>
                                <th class="px-4 py-3 text-left">Dokter</th>
                                <th class="px-4 py-3 text-left">Diagnosa</th>
                                <th class="px-4 py-3 text-left">Tindakan</th>
                                <th class="px-4 py-3 text-left">Resep</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($patient->medicalRecords as $record)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap font-semibold text-gray-900">
                                        {{ $record->visit_date ? \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($record->symptoms ?? '-', 40) }}</td>
                                    <td class="px-4 py-3">{{ $record->doctor->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($record->diagnosis ?? '-', 40) }}</td>
                                    <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($record->treatment ?? '-', 40) }}</td>
                                    <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($record->prescription ?? '-', 40) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('medical_records.show', $record->id) }}" class="text-blue-600 font-semibold">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada riwayat pemeriksaan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Form Section --}}
        <div id="examinationForm" class="bg-white rounded-2xl border border-gray-200 p-6 @if(isset($patient)) hidden @endif">
            <form id="medical-record-form" action="{{ route('dokter.medical_records.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <label for="visit_date" class="text-sm font-semibold text-gray-700">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                        <input type="date" id="visit_date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required
                               class="mt-2 w-full rounded-xl border-gray-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-200">
                    </div>
                    <div class="md:col-span-1">
                        <label class="text-sm font-semibold text-gray-700">Nomor Antrian</label>
                        <input type="text" disabled value="{{ isset($queue) && $queue ? '#'.$queue->queue_number : '-' }}"
                               class="mt-2 w-full rounded-xl border-gray-200 bg-gray-50 text-gray-900 font-semibold">
                    </div>
                    <div class="md:col-span-1 flex items-end justify-end text-sm text-gray-500">
                        <div class="text-right">
                            <p>Tanggal hari ini</p>
                            <p class="font-semibold text-gray-900">{{ now()->translatedFormat('l, d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label for="symptoms" class="block text-sm font-semibold text-gray-700">Gejala</label>
                            <textarea id="symptoms" name="symptoms" rows="3"
                                      class="mt-2 w-full rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('symptoms') }}</textarea>
                        </div>
                        <div>
                            <label for="diagnosis" class="block text-sm font-semibold text-gray-700">Diagnosis</label>
                            <textarea id="diagnosis" name="diagnosis" rows="3"
                                      class="mt-2 w-full rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('diagnosis') }}</textarea>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="treatment" class="block text-sm font-semibold text-gray-700">Tindakan / Perawatan</label>
                            <textarea id="treatment" name="treatment" rows="3"
                                      class="mt-2 w-full rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('treatment') }}</textarea>
                        </div>
                        <div>
                            <label for="prescription" class="block text-sm font-semibold text-gray-700">Resep Obat</label>
                            <textarea id="prescription" name="prescription" rows="3" placeholder="Contoh: Amoxicillin 250mg, 2x sehari setelah makan"
                                      class="mt-2 w-full rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('prescription') }}</textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700">Catatan Tambahan</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="mt-2 w-full rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('notes') }}</textarea>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <a href="{{ route('dokter.queues.index') }}"
                       class="text-gray-500 hover:text-gray-800 font-medium px-4 py-2 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 rounded-2xl text-white font-semibold bg-gradient-to-r from-purple-500 to-blue-500 shadow-lg hover:shadow-xl transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Rekam Medis
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@if(isset($patient))
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toggleBtn = document.getElementById('toggleFormButton');
            var formSection = document.getElementById('examinationForm');

            if (toggleBtn && formSection) {
                toggleBtn.addEventListener('click', function () {
                    formSection.classList.remove('hidden');
                    formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            }
        });
    </script>
    @endpush
@endif