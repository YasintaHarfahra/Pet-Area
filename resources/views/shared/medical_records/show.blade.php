{{-- Menentukan layout secara dinamis berdasarkan role user --}}
@extends(Auth::user()->role === 'admin' ? 'layouts.admin_app' : 'layouts.dokter_app')

{{-- Menentukan judul halaman secara dinamis --}}
@section('title', 'Detail Rekam Medis - ' . (Auth::user()->role === 'admin' ? 'Admin' : 'Dokter'))
@section('header_title', 'Detail Rekam Medis')

@section('content')
    <div class="bg-gradient-to-br from-slate-50 to-pink-50 min-h-screen pb-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            {{-- Header with Back Button --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.medical_records.index') : route('dokter.medical_records.index') }}" 
                       class="w-9 h-9 bg-white rounded-lg border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-pink-50 hover:text-[#970747] hover:border-pink-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black bg-gradient-to-r from-[#970747] to-[#970747] bg-clip-text text-transparent mb-0.5">
                            Detail Rekam Medis
                        </h1>
                        <p class="text-xs text-slate-600">ID: #{{ $medicalRecord->id_medical_record }}</p>
                    </div>
                </div>
                
                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Tanggal Kunjungan</p>
                    <p class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($medicalRecord->visit_date)->translatedFormat('d M Y') }}</p>
                    <p class="text-xs text-slate-600">{{ \Carbon\Carbon::parse($medicalRecord->visit_date)->translatedFormat('l') }}</p>
                </div>
                
                @if(Auth::user()->role === 'dokter')
                    @can('manage-medical-records')
                    <div class="flex items-center gap-2">
                        <a href="{{ route('dokter.riwayat.edit', $medicalRecord->id_medical_record) }}" class="px-3 py-2 bg-yellow-50 text-yellow-700 rounded-lg text-xs font-semibold hover:bg-yellow-100 transition-colors border border-yellow-200 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('dokter.medical_records.destroy', $medicalRecord->id_medical_record) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 bg-red-50 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-100 transition-colors border border-red-200 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                    @endcan
                @endif
            </div>

{{-- Patient Info Card --}}
            <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden mb-6">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-pink-50 to-pink-50 px-4 py-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#970747] to-[#970747] flex items-center justify-center text-white font-bold text-sm">
                            {{ substr($medicalRecord->patient->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">{{ $medicalRecord->patient->name }}</h3>
                            <p class="text-xs text-slate-500">{{ $medicalRecord->patient->species }} • {{ $medicalRecord->patient->breed ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Content Grid --}}
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        {{-- Owner Info --}}
                        <div class="bg-slate-50 rounded-lg p-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Pemilik</p>
                            <p class="text-sm font-bold text-slate-900">{{ $medicalRecord->patient->owner_name }}</p>
                            <p class="text-xs text-slate-600 mt-1">{{ $medicalRecord->patient->owner_phone ?? '-' }}</p>
                        </div>

                        {{-- RM Number --}}
                        <div class="bg-pink-50 rounded-lg p-3 border border-pink-200">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">RM Number</p>
                            <p class="text-sm font-mono text-[#7a0538] font-bold">{{ $medicalRecord->patient->medical_record_number ?? 'N/A' }}</p>
                        </div>

                        {{-- Weight Info --}}
                        <div class="bg-slate-50 rounded-lg p-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Berat Badan</p>
                            <p class="text-sm font-bold text-slate-900">{{ $medicalRecord->patient->weight ? $medicalRecord->patient->weight . ' kg' : 'Tidak ada data' }}</p>
                        </div>

                        {{-- Age Info --}}
                        <div class="bg-slate-50 rounded-lg p-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Umur</p>
                            @if($medicalRecord->patient->date_of_birth)
                                @php
                                    $dob = \Carbon\Carbon::parse($medicalRecord->patient->date_of_birth);
                                    $diff = $dob->diff(\Carbon\Carbon::now());
                                @endphp
                                <p class="text-sm font-bold text-slate-900">{{ $diff->y }}y {{ $diff->m }}m</p>
                            @else
                                <p class="text-sm text-slate-600">-</p>
                            @endif
                        </div>
                    </div>

                    {{-- Address (Full Width) --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Alamat</p>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $medicalRecord->patient->owner_address ?? 'Tidak ada data' }}</p>
                    </div>

                    {{-- Action Button --}}
                    <a href="{{ route('patients.show', $medicalRecord->patient->id_pet) }}" class="w-full py-2.5 text-center text-xs font-semibold text-white bg-gradient-to-r from-[#970747] to-[#970747] hover:from-[#7a0538] hover:to-[#7a0538] rounded-lg transition-colors flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Lihat Profil Lengkap Pasien
                    </a>
                </div>
            </div>

            {{-- Medical Details --}}
            <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-pink-50 to-pink-50 px-4 sm:px-5 py-3 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#970747]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Hasil Pemeriksaan Medis
                    </h2>
                </div>

                <div class="p-4 sm:p-5 space-y-4">
                    {{-- Symptoms & Diagnosis Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Symptoms --}}
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-600">Gejala / Keluhan</p>
                            </div>
                            <div class="bg-orange-50 border border-orange-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 leading-relaxed">
                                {{ $medicalRecord->symptoms ?? 'Tidak ada data' }}
                            </div>
                        </div>

                        {{-- Diagnosis --}}
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-600">Diagnosis</p>
                            </div>
                            <div class="bg-red-50 border border-red-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 leading-relaxed">
                                {{ $medicalRecord->diagnosis ?? 'Belum ada diagnosis' }}
                            </div>
                        </div>
                    </div>

                    {{-- Treatment --}}
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-600">Tindakan / Perawatan</p>
                        </div>
                        <div class="bg-teal-50 border border-teal-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 leading-relaxed">
                            @if($medicalRecord->treatments && $medicalRecord->treatments->count())
                                <ul class="list-disc pl-5">
                                    @foreach($medicalRecord->treatments as $treatment)
                                        <li>{{ $treatment->nama_tindakan }} <span class="text-xs text-slate-500">({{ $treatment->kategori }})</span></li>
                                    @endforeach
                                </ul>
                            @else
                                Tidak ada tindakan khusus
                            @endif
                        </div>
                    </div

                    {{-- Prescription --}}
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-[#970747]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-600">Resep Obat</p>
                        </div>
                        <div class="bg-pink-50 border border-pink-200 rounded-lg px-3 py-2.5 text-sm font-medium text-[#7a0538] leading-relaxed">
                            @if($medicalRecord->prescriptionItems && $medicalRecord->prescriptionItems->count())
                                <ul class="list-decimal pl-5">
                                    @foreach($medicalRecord->prescriptionItems as $item)
                                        <li>{{ $item->medicine->nama_obat ?? 'Obat tidak ditemukan' }} - {{ $item->dosage }} {{ $item->unit }} <span class="text-xs text-slate-500">({{ $item->instructions }})</span></li>
                                    @endforeach
                                </ul>
                            @else
                                Tidak ada resep obat
                            @endif
                        </div>
                    </div

                    {{-- Notes --}}
                    @if($medicalRecord->notes)
                    <div class="pt-3 border-t border-slate-100">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-600">Catatan Tambahan</p>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 italic leading-relaxed border-l-4 border-l-slate-400">
                            {{ $medicalRecord->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection