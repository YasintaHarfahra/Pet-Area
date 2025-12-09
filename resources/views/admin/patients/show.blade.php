@if(Auth::user()->role === 'admin')
    @extends('layouts.admin_app')
    @section('title', 'Detail Pasien: ' . ($patient->name ?? ''), ' - Admin')
    @section('header_title', 'Detail Pasien')
@else {{-- Dokter --}}
    @extends('layouts.dokter_app')
    @section('title', 'Detail Pasien: ' . ($patient->name ?? ''), ' - Dokter')
    @section('header_title', 'Detail Pasien')
@endif

@section('content')
    <div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-md max-w-3xl">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Detail Pasien: {{ $patient->name }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Hewan</h3>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Nomor RM:</strong> {{ $patient->medical_record_number ?? '-' }}</p> {{-- TAMBAHKAN INI --}}
                </div>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Nama:</strong> {{ $patient->name }}</p>
                </div>
                {{-- ... (sisa info hewan sama) ... --}}
            </div>
            {{-- ... (informasi pemilik sama) ... --}}
        </div>

        {{-- ... (riwayat rekam medis dan tombol aksi sama) ... --}}
    </div>
@endsection