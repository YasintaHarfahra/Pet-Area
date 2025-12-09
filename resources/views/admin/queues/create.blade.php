@extends('layouts.admin_app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Antrian Baru</h1>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-6">
        <form action="{{ route('admin.queues.store') }}" method="POST" id="queueForm">
            @csrf

            <div class="mb-6">
                <label for="patient_search" class="block text-sm font-medium text-gray-700 mb-2">Pilih Pasien <span class="text-red-500">*</span></label>
                <input type="text" 
                        id="patient_search" 
                        name="patient_search"
                        list="patient_list"
                        placeholder="Ketik nama atau nomor RM..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        autocomplete="off"
                        required>
                <datalist id="patient_list">
                    {{-- Pastikan data pasien sudah dikirim ke view ini dari Controller --}}
                    @foreach($patients as $patient)
                        {{-- Menggunakan format data yang mudah dipetakan di JS --}}
                        <option value="{{ $patient->name }} (RM: {{ $patient->medical_record_number ?? 'No RM' }})" data-id="{{ $patient->id }}">
                    @endforeach
                </datalist>
                <input type="hidden" name="patient_id" id="patient_id" value="{{ old('patient_id') }}">
                <div id="selected_patient_info" class="mt-2 {{ old('patient_id') ? '' : 'hidden' }}">
                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm font-medium text-green-900">✓ Pasien terpilih: <span id="selected_patient_name" class="font-bold"></span></p>
                    </div>
                </div>
                @error('patient_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="queue_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input type="date" name="queue_date" id="queue_date" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="{{ date('Y-m-d') }}" required>
                @error('queue_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="complaint" class="block text-sm font-medium text-gray-700 mb-2">Keluhan</label>
                <textarea name="complaint" id="complaint" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Tuliskan keluhan pasien..."></textarea>
                @error('complaint')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    Tambah Antrian
                </button>
                <a href="{{ route('admin.queues.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('patient_search');
    const patientIdInput = document.getElementById('patient_id');
    const selectedInfo = document.getElementById('selected_patient_info');
    const selectedName = document.getElementById('selected_patient_name');
    
    // 1. MENDAPATKAN DATA DARI DATALIST OPTIONS
    const patientList = document.getElementById('patient_list');
    const patientMap = {}; // Key: Nilai Datalist, Value: ID Pasien

    // Iterasi melalui opsi datalist untuk mengisi patientMap
    Array.from(patientList.options).forEach(option => {
        const patientId = option.dataset.id;
        if (patientId) {
            patientMap[option.value.trim()] = patientId;
        }
    });

    // 2. FUNGSI UNTUK MEMPROSES PILIHAN (Dipanggil saat change/blur)
    function processSelection() {
        const selectedValue = searchInput.value.trim();
        
        // Cek apakah nilai input cocok dengan salah satu key di map
        if (selectedValue && patientMap.hasOwnProperty(selectedValue)) {
            const selectedId = patientMap[selectedValue];
            patientIdInput.value = selectedId;
            
            // Ekstrak nama untuk feedback
            const namaMatch = selectedValue.match(/^(.+?)\s*\(RM:/) || selectedValue.match(/^(.+?)\s*\(/);
            const displayNama = namaMatch ? namaMatch[1].trim() : selectedValue;
            
            selectedName.textContent = displayNama;
            selectedInfo.classList.remove('hidden');

        } else {
            // Jika nilai input tidak cocok dengan item yang valid, HAPUS ID
            patientIdInput.value = '';
            selectedInfo.classList.add('hidden');
        }
    }

    // 3. LISTENERS
    
    // Event 'change' (Terpicu saat pengguna memilih dari datalist atau input kehilangan fokus)
    searchInput.addEventListener('change', processSelection);

    // Event 'input' (Hanya untuk mereset ID jika input dikosongkan)
    searchInput.addEventListener('input', function() {
        if (this.value.trim() === '') {
            patientIdInput.value = '';
            selectedInfo.classList.add('hidden');
        }
    });
    
    // 4. INICIALISASI
    // Jika ada old('patient_id') dari kegagalan validasi, tampilkan informasinya
    if (patientIdInput.value) {
        // Cari nilai input yang sesuai di datalist untuk menampilkan feedback yang benar
        const initialOption = Array.from(patientList.options).find(opt => opt.dataset.id == patientIdInput.value);
        if (initialOption) {
             searchInput.value = initialOption.value;
             processSelection(); // Panggil processSelection untuk mengisi feedback
        }
    }
});
</script>
@endpush
@endsection