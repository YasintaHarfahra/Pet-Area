{{-- Menentukan layout secara dinamis berdasarkan role user --}}
@extends(Auth::user()->role === 'admin' ? 'layouts.admin_app' : 'layouts.dokter_app')

{{-- Menentukan judul halaman secara dinamis --}}
@section('title', 'Detail Rekam Medis - ' . (Auth::user()->role === 'admin' ? 'Admin' : 'Dokter'))
@section('header_title', 'Detail Rekam Medis')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6 fade-in pb-8">
        
        {{-- Header with Back Button --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.medical_records.index') : route('dokter.medical_records.index') }}" 
                   class="w-10 h-10 bg-white rounded-xl border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Rekam Medis</h1>
                    <p class="text-sm text-gray-500">ID: #{{ $medicalRecord->id }} • {{ \Carbon\Carbon::parse($medicalRecord->visit_date)->translatedFormat('d F Y') }}</p>
                </div>
            </div>
            
            @if(Auth::user()->role === 'dokter')
                @can('manage-medical-records')
                <div class="flex items-center gap-3">
                    <a href="{{ route('dokter.medical_records.edit', $medicalRecord->id) }}" class="px-4 py-2 bg-yellow-50 text-yellow-600 rounded-xl text-sm font-bold hover:bg-yellow-100 transition-colors border border-yellow-100 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit
                    </a>
                    <form action="{{ route('dokter.medical_records.destroy', $medicalRecord->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-bold hover:bg-red-100 transition-colors border border-red-100 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus
                        </button>
                    </form>
                </div>
                @endcan
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Column 1: Patient & Doctor Info --}}
            <div class="space-y-6">
                {{-- Patient Card --}}
                <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Informasi Pasien</h3>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 text-2xl font-bold">
                            {{ substr($medicalRecord->patient->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900">{{ $medicalRecord->patient->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $medicalRecord->patient->species }} • {{ $medicalRecord->patient->breed ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Pemilik</span>
                            <span class="text-sm font-medium text-gray-900">{{ $medicalRecord->patient->owner_name }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Telepon</span>
                            <span class="text-sm font-medium text-gray-900">{{ $medicalRecord->patient->owner_phone ?? '-' }}</span>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('patients.show', $medicalRecord->patient->id) }}" class="block w-full py-2 text-center text-sm font-bold text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                                Lihat Profil Pasien
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Doctor Card --}}
                <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Dokter Pemeriksa</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500 font-bold">
                            Dr
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-gray-900">{{ $medicalRecord->doctor->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $medicalRecord->doctor->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Column 2 & 3: Medical Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Medical Summary Card --}}
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6">Hasil Pemeriksaan</h3>
                    
                    <div class="space-y-8">
                        {{-- Gejala & Diagnosis Row --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Gejala --}}
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-900">Gejala (Anamnesa)</h3>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl text-sm text-gray-600 leading-relaxed min-h-[80px]">
                                    {{ $medicalRecord->symptoms ?? 'Tidak ada data gejala.' }}
                                </div>
                            </div>

                            {{-- Diagnosis --}}
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-900">Diagnosis</h3>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl text-sm text-gray-600 leading-relaxed min-h-[80px]">
                                    {{ $medicalRecord->diagnosis ?? 'Belum ada diagnosis.' }}
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        {{-- Tindakan (Full Width) --}}
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center text-teal-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-900">Tindakan / Perawatan</h3>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl text-sm text-gray-600 leading-relaxed">
                                {!! nl2br(e($medicalRecord->treatment ?? 'Tidak ada tindakan khusus.')) !!}
                            </div>
                        </div>

                        {{-- Resep (Full Width) --}}
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-900">Resep Obat</h3>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-2xl text-sm text-blue-800 leading-relaxed border border-blue-100">
                                {!! nl2br(e($medicalRecord->prescription ?? 'Tidak ada resep obat.')) !!}
                            </div>
                        </div>

                        {{-- Notes (Full Width) --}}
                        @if($medicalRecord->notes)
                        <div class="pt-4 border-t border-gray-100">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Catatan Tambahan</h3>
                            <p class="text-gray-600 text-sm italic bg-gray-50 p-4 rounded-2xl">
                                "{{ $medicalRecord->notes }}"
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection