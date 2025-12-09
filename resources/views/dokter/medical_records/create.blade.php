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

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-4xl mx-auto">
        <form action="{{ route('dokter.medical_records.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="mb-4">
                    @if(isset($patient))
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                        @if(isset($queue_id))
                            <input type="hidden" name="queue_id" value="{{ $queue_id }}">
                        @endif
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pasien</label>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="font-medium text-gray-900">{{ $patient->name }}</p>
                            <p class="text-sm text-gray-600">Pemilik: {{ $patient->owner_name }}</p>
                        </div>
                    @else
                        <label for="patient_id" class="block text-gray-700 text-sm font-bold mb-2">Pilih Pasien <span class="text-red-500">*</span></label>
                        <select id="patient_id" name="patient_id" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">-- Pilih Pasien --</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }} (Pemilik: {{ $patient->owner_name }})
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="mb-4">
                    <label for="visit_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                    <input type="date" id="visit_date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="symptoms" class="block text-gray-700 text-sm font-bold mb-2">Gejala</label>
                    <textarea id="symptoms" name="symptoms" rows="4"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('symptoms') }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="diagnosis" class="block text-gray-700 text-sm font-bold mb-2">Diagnosis</label>
                    <textarea id="diagnosis" name="diagnosis" rows="4"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('diagnosis') }}</textarea>
                </div>
            </div>
            <div>
                <div class="mb-4">
                    <label for="treatment" class="block text-gray-700 text-sm font-bold mb-2">Tindakan / Perawatan</label>
                    <textarea id="treatment" name="treatment" rows="4"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('treatment') }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="prescription" class="block text-gray-700 text-sm font-bold mb-2">Resep Obat</label>
                    <textarea id="prescription" name="prescription" rows="4" placeholder="Contoh: Amoxicillin 250mg, 2x sehari setelah makan"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('prescription') }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Catatan Tambahan</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

            <div class="flex items-center justify-between mt-8">
                <button type="submit"
                        class="btn-primary text-white px-6 py-3 rounded-xl font-semibold flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Simpan Rekam Medis</span>
                </button>
                <a href="{{ route('dokter.medical_records.index') }}" 
                   class="text-gray-600 hover:text-gray-800 font-medium px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection