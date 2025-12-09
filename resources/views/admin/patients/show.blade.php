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
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold gradient-text mb-2">Detail Pasien</h1>
            <p class="text-gray-600">Informasi Lengkap & Riwayat Pemeriksaan</p>
        </div>
        <a href="{{ Auth::user()->role === 'admin' ? route('admin.patients.index') : route('dokter.patients.index') }}" 
           class="text-gray-600 hover:text-gray-800 font-medium px-4 py-2 rounded-lg hover:bg-gray-100">
            ← Kembali
        </a>
    </div>
</div>

<!-- Grid: Identitas Pasien + Riwayat Pemeriksaan -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Identitas Pasien (1/3) -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-4">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Identitas Pasien</h2>
            </div>
            <div class="p-6 space-y-4">
                <!-- Data Hewan -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                    <h3 class="text-sm font-semibold text-purple-900 mb-3">Data Hewan</h3>
                    <dl class="space-y-2 text-sm">
                        <div>
                            <dt class="text-purple-700 font-medium">Nomor RM:</dt>
                            <dd class="text-purple-900 font-mono font-bold">{{ $patient->medical_record_number ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-purple-700 font-medium">Nama Hewan:</dt>
                            <dd class="text-purple-900 font-semibold">{{ $patient->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-purple-700 font-medium">Jenis Hewan:</dt>
                            <dd class="text-purple-900">{{ $patient->species }}</dd>
                        </div>
                        <div>
                            <dt class="text-purple-700 font-medium">Ras:</dt>
                            <dd class="text-purple-900">{{ $patient->breed ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-purple-700 font-medium">Umur:</dt>
                            <dd class="text-purple-900">
                                @if($patient->date_of_birth)
                                    {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} tahun
                                    <span class="text-xs text-gray-600">(lahir {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d M Y') }})</span>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-purple-700 font-medium">Jenis Kelamin:</dt>
                            <dd class="text-purple-900">{{ $patient->gender ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-purple-700 font-medium">Berat Badan:</dt>
                            <dd class="text-purple-900">{{ $patient->weight ? $patient->weight . ' kg' : '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Data Pemilik -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
                    <h3 class="text-sm font-semibold text-orange-900 mb-3">Data Pemilik</h3>
                    <dl class="space-y-2 text-sm">
                        <div>
                            <dt class="text-orange-700 font-medium">Nama Pemilik:</dt>
                            <dd class="text-orange-900 font-semibold">{{ $patient->owner_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-orange-700 font-medium">No. HP:</dt>
                            <dd class="text-orange-900">{{ $patient->owner_phone ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-orange-700 font-medium">Alamat:</dt>
                            <dd class="text-orange-900 break-words text-xs">{{ $patient->owner_address ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-orange-700 font-medium">Email:</dt>
                            <dd class="text-orange-900 break-all text-xs">{{ $patient->owner_email ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Riwayat Medis Umum -->
                @if($patient->medical_history)
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
                        <h3 class="text-sm font-semibold text-red-900 mb-2">Riwayat Medis Umum</h3>
                        <p class="text-sm text-red-900">{{ $patient->medical_history }}</p>
                    </div>
                @endif

                @if(Auth::user()->role === 'admin')
                    <div class="flex space-x-2 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.patients.edit', $patient->id) }}" 
                           class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-3 rounded-lg text-center transition-colors text-sm">
                            Edit
                        </a>
                        <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" class="flex-1" 
                              onsubmit="return confirm('Yakin ingin menghapus pasien ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-3 rounded-lg transition-colors text-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Riwayat Pemeriksaan (2/3) -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Riwayat Pemeriksaan</h2>
                <p class="text-sm text-gray-600 mt-1">Total: <span class="font-bold text-gray-900">{{ $patient->medicalRecords->count() }}</span> kali pemeriksaan</p>
            </div>

            @if($patient->medicalRecords->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($patient->medicalRecords as $record)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 pb-4 border-b border-gray-100">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Pemeriksaan</p>
                                    <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($record->visit_date)->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($record->visit_date)->diffForHumans() }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Dokter Pemeriksa</p>
                                    <p class="text-gray-900 font-semibold">{{ $record->doctor->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ optional($record->doctor)->email ?? '' }}</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                @if($record->symptoms)
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Gejala
                                        </p>
                                        <p class="text-sm text-gray-600 bg-yellow-50 p-3 rounded mt-1 border-l-4 border-yellow-500">{{ $record->symptoms }}</p>
                                    </div>
                                @endif

                                @if($record->diagnosis)
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Diagnosis
                                        </p>
                                        <p class="text-sm text-gray-600 bg-blue-50 p-3 rounded mt-1 border-l-4 border-blue-500">{{ $record->diagnosis }}</p>
                                    </div>
                                @endif

                                @if($record->treatment)
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h-2m0 0h2m-2 0V8m0 2v2m0-6h4a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2h4m14 0h2a2 2 0 012 2v12a2 2 0 01-2 2h-2"></path>
                                            </svg>
                                            Tindakan
                                        </p>
                                        <p class="text-sm text-gray-600 bg-green-50 p-3 rounded mt-1 border-l-4 border-green-500">{{ $record->treatment }}</p>
                                    </div>
                                @endif

                                @if($record->prescription)
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.228 6.228 2.75 7.079 2.75 8.25c0 7.168 3.435 9.425 9.25 12.25m0-13c5.772-2.825 9.25-5.082 9.25-12.25 0-1.171-3.478-2.022-9.25-2.25m0 13v-13"></path>
                                            </svg>
                                            Resep Obat
                                        </p>
                                        <p class="text-sm text-gray-600 bg-purple-50 p-3 rounded mt-1 border-l-4 border-purple-500">{{ $record->prescription }}</p>
                                    </div>
                                @endif

                                @if($record->notes)
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Catatan
                                        </p>
                                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded mt-1 border-l-4 border-gray-500">{{ $record->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Riwayat Pemeriksaan</h3>
                            <p class="text-gray-500">Pasien ini adalah pasien baru atau belum pernah diperiksa sebelumnya.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection