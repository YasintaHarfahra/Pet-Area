@extends('layouts.dokter_app')

@section('title', 'Edit Rekam Medis - Dokter')

@section('content')
    
    {{-- Tombol Kembali --}}
    <a href="{{ route('dokter.medical_records.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-4 transition duration-150 ease-in-out">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Rekam Medis
    </a>

    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Edit Rekam Medis</h1>
        <p class="text-gray-600">Perbarui catatan medis untuk pasien **{{ $medicalRecord->patient->name ?? 'N/A' }}**.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-6 shadow-sm">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 relative">
        <form action="{{ route('dokter.medical_records.update', $medicalRecord->id) }}" method="POST" id="medisForm">
        @csrf
        @method('PUT')
        
        {{-- INFORMASI PASIEN & TANGGAL (Selalu Terlihat) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 pb-6 border-b border-gray-100">
            
            {{-- Data Pasien --}}
            <div class="p-4 rounded-xl bg-purple-50/50 border border-purple-200 md:col-span-1">
                <h3 class="text-sm font-bold text-purple-800 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Data Pasien
                </h3>
                <p class="font-extrabold text-gray-900 text-sm">{{ $medicalRecord->patient->name ?? 'N/A' }}</p>
                <p class="text-xs text-gray-600">RM: {{ $medicalRecord->patient->medical_record_number ?? 'No RM' }}</p>
                <p class="text-xs text-gray-600">Pemilik: {{ $medicalRecord->patient->owner_name ?? '-' }}</p>
                @if($medicalRecord->patient)
                    @php
                        $dob = \Carbon\Carbon::parse($medicalRecord->patient->date_of_birth ?? now());
                        $diff = $dob->diff(\Carbon\Carbon::now());
                    @endphp
                    <p class="text-xs text-gray-600">Jenis: {{ $medicalRecord->patient->species ?? '-' }} • Ras: {{ $medicalRecord->patient->breed ?? '-' }} • Umur: {{ $diff->y }} th {{ $diff->m }} bln</p>
                @endif
                <input type="hidden" name="patient_id" value="{{ $medicalRecord->patient_id }}">
            </div>
            
            {{-- Tanggal Kunjungan --}}
            <div class="md:col-span-2">
                <label for="visit_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input type="date" id="visit_date" name="visit_date" value="{{ old('visit_date', $medicalRecord->visit_date) }}" required
                        class="shadow appearance-none border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">
            </div>
        </div>

        {{-- NAVIGASI TABS - STICKY --}}
        <div class="sticky top-0 z-10 bg-white pt-2 pb-4 border-b border-gray-200 mb-6" id="medis-tabs">
            <div class="flex space-x-1 overflow-x-auto no-scrollbar">
                <button type="button" onclick="switchTab('subjective')" id="tab-subjective" class="tab-btn flex-1 whitespace-nowrap px-4 py-3 text-sm font-semibold text-gray-900 border-b-2 border-blue-600 hover:bg-gray-50 transition-colors duration-200 focus:outline-none">1. Subjective</button>
                <button type="button" onclick="switchTab('objective')" id="tab-objective" class="tab-btn flex-1 whitespace-nowrap px-4 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:border-gray-300 hover:bg-gray-50 transition-colors duration-200 focus:outline-none">2. Objective</button>
                <button type="button" onclick="switchTab('assessment')" id="tab-assessment" class="tab-btn flex-1 whitespace-nowrap px-4 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:border-gray-300 hover:bg-gray-50 transition-colors duration-200 focus:outline-none">3. Assessment</button>
                <button type="button" onclick="switchTab('plan')" id="tab-plan" class="tab-btn flex-1 whitespace-nowrap px-4 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:border-gray-300 hover:bg-gray-50 transition-colors duration-200 focus:outline-none">4. Plan</button>
            </div>
        </div>

        {{-- KONTEN TABS --}}
        <div class="min-h-[400px]">
            
            {{-- TAB 1: SUBJECTIVE --}}
            <div id="content-subjective" class="tab-content block">
                <h3 class="text-xl font-bold text-gray-800 mb-4">1. Subjective: Keluhan Utama & Riwayat Penyakit</h3>
                <label for="symptoms" class="block text-gray-700 text-sm font-bold mb-2">Gejala (Anamnesis)</label>
                <textarea id="symptoms" name="symptoms" rows="12" placeholder="Keluhan utama dan riwayat penyakit..."
                            class="shadow appearance-none border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">{{ old('symptoms', $medicalRecord->symptoms) }}</textarea>
            </div>

            {{-- TAB 2: OBJECTIVE --}}
            <div id="content-objective" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">2. Objective: Pemeriksaan & Tindakan</h3>
                <label for="treatment" class="block text-gray-700 text-sm font-bold mb-2">Tindakan / Perawatan</label>
                <textarea id="treatment" name="treatment" rows="12" placeholder="Contoh: Pemberian cairan infus, debridement luka, rawat inap, temuan fisik."
                            class="shadow appearance-none border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">{{ old('treatment', $medicalRecord->treatment) }}</textarea>
            </div>

            {{-- TAB 3: ASSESSMENT --}}
            <div id="content-assessment" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">3. Assessment: Kesimpulan Diagnosis</h3>
                <label for="diagnosis" class="block text-gray-700 text-sm font-bold mb-2">Diagnosis <span class="text-red-500">*</span></label>
                <textarea id="diagnosis" name="diagnosis" rows="12" required placeholder="Diagnosis klinis yang ditemukan..."
                            class="shadow appearance-none border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">{{ old('diagnosis', $medicalRecord->diagnosis) }}</textarea>
            </div>

            {{-- TAB 4: PLAN --}}
            <div id="content-plan" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">4. Plan: Rencana Pengobatan & Saran</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="prescription" class="block text-gray-700 text-sm font-bold mb-2">Resep Obat</label>
                        <textarea id="prescription" name="prescription" rows="10" placeholder="Contoh: Amoxicillin 250mg, 2x sehari; Vitamin B Kompleks 1x sehari."
                                    class="shadow appearance-none border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">{{ old('prescription', $medicalRecord->prescription) }}</textarea>
                    </div>
                    <div>
                        <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Catatan Tambahan / Saran</label>
                        <textarea id="notes" name="notes" rows="10" placeholder="Saran follow-up, prognosis, atau instruksi pemilik."
                                    class="shadow appearance-none border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">{{ old('notes', $medicalRecord->notes) }}</textarea>
                    </div>
                </div>
            </div>

        </div>
        
        {{-- Footer Aksi (Global) --}}
        <div class="flex items-center justify-end mt-8 border-t pt-4 border-gray-100 sticky bottom-0 bg-white pb-4 z-10 space-x-4">
            <a href="{{ route('dokter.medical_records.index') }}" 
               class="text-gray-600 hover:text-gray-800 font-medium px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold flex items-center space-x-2 transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Update Rekam Medis</span>
            </button>
        </div>

        </form>
    </div>

@push('scripts')
<script>
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
            content.classList.remove('block');
        });

        // Show selected tab content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        document.getElementById('content-' + tabName).classList.add('block');

        // Reset all tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-blue-600', 'text-gray-900');
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        // Highlight selected tab button
        const activeBtn = document.getElementById('tab-' + tabName);
        activeBtn.classList.remove('border-transparent', 'text-gray-500');
        activeBtn.classList.add('border-blue-600', 'text-gray-900');
    }
</script>
@endpush
@endsection