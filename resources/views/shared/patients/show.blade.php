{{-- Menentukan layout secara dinamis berdasarkan role user --}}
@extends(Auth::user()->role === 'admin' ? 'layouts.admin_app' : 'layouts.dokter_app')

{{-- Menentukan judul halaman secara dinamis --}}
@section('title', 'Detail Rekam Medis - ' . (Auth::user()->role === 'admin' ? 'Admin' : 'Dokter'))
@section('header_title', 'Detail Pasien')

@section('content')
    <div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-md max-w-3xl">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Detail Pasien: {{ $patient->name }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Hewan</h3>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Nomor RM:</strong> {{ $patient->medical_record_number ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Nama:</strong> {{ $patient->name }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Jenis:</strong> {{ $patient->species }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Ras:</strong> {{ $patient->breed ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Tanggal Lahir:</strong> {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->translatedFormat('d F Y') : '-' }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Jenis Kelamin:</strong> {{ $patient->gender ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Berat:</strong> {{ $patient->weight ? $patient->weight . ' kg' : '-' }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Riwayat Medis Umum:</strong> {{ $patient->medical_history ?? '-' }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Pemilik</h3>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Nama Pemilik:</strong> {{ $patient->owner_name }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>No. HP:</strong> {{ $patient->owner_phone ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Alamat:</strong> {{ $patient->owner_address ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-600"><strong>Email:</strong> {{ $patient->owner_email ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-between">
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.patients.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md transition duration-150">Kembali ke Daftar Pasien</a>
                <div class="space-x-2">
                    <a href="{{ route('admin.patients.edit', $patient->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-md transition duration-150">Edit Pasien</a>
                    <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pasien ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-700 text-white font-bold py-2 px-4 rounded-md transition duration-150">Hapus Pasien</button>
                    </form>
                </div>
            @else {{-- Dokter --}}
                <a href="{{ route('dokter.patients.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md transition duration-150">Kembali ke Daftar Pasien</a>
            @endif
        </div>

        {{-- Bagian untuk Riwayat Rekam Medis Pasien ini --}}
        <h3 class="text-xl font-semibold text-gray-700 mt-8 mb-3 border-b pb-2">Riwayat Rekam Medis</h3>
        @if($patient->medicalRecords->isNotEmpty())
            <ul class="list-disc pl-5">
                @foreach ($patient->medicalRecords->sortByDesc('visit_date') as $record)
                    <li class="mb-2">
                        <span class="font-medium">{{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d F Y') }}</span> -
                        Diagnosa: {{ Str::limit($record->diagnosis, 50) ?? '-' }}
                        <a href="{{ route('medical_records.show', $record->id) }}" class="text-blue-500 hover:underline ml-2">Lihat Detail</a>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800 mt-4">
                <p>Belum ada rekam medis untuk pasien ini.</p>
            </div>
        @endif

    </div>
@endsection