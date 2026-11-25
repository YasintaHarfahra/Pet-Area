@extends('layouts.admin_app')

@section('title', 'Detail Rujukan')
@section('header_title', 'Surat Rujukan')

@section('content')
    <div class="max-w-3xl mx-auto px-4 pb-8">
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('admin.referrals.index') }}" class="text-sm text-[#970747] hover:text-[#7a0538] flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Rujukan
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-slate-800">{{ $referral->nomor_rujukan }}</h1>
                    <p class="text-sm text-slate-500">
                        @if($referral->status_cetak === 'pending')
                            <span class="text-amber-600">Belum Dicetak</span>
                        @else
                            <span class="text-emerald-600">Dicetak pada {{ $referral->printed_at->format('d M Y H:i') }}</span>
                        @endif
                    </p>
                </div>
                <a href="{{ route('admin.referrals.print', $referral) }}" target="_blank"
                   class="px-4 py-2 bg-[#970747] hover:bg-[#7a0538] text-white rounded-lg font-medium text-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </a>
            </div>
        </div>

        @php
            $pet = $referral->medicalRecord?->pet;
            $owner = $pet?->owner ?? $referral->animalMedicalRecord?->animal?->owner ?? null;
            $namaHewan = $pet?->nama_hewan ?? $referral->animalMedicalRecord?->animal?->nama_hewan ?? '-';
            $jenis = $pet?->jenis ?? $referral->animalMedicalRecord?->animal?->jenis ?? '-';
            $ras = $pet?->ras ?? $referral->animalMedicalRecord?->animal?->ras ?? '-';
            $namaPemilik = $owner?->nama ?? '-';
            $noHpPemilik = $owner?->no_hp ?? '-';
            
            // Diagnosa Sementara: cek referral dulu, jika kosong cek medical record (coba diagnosis dulu, jika tidak ada coba diagnosa)
            $diagnosaTeks = $referral->diagnosa_sementara;
            if (empty($diagnosaTeks) || trim($diagnosaTeks) === '-' || trim($diagnosaTeks) === '') {
                $diagnosaTeks = $referral->medicalRecord?->diagnosis ?? $referral->medicalRecord?->diagnosa ?? $referral->animalMedicalRecord?->diagnosa ?? '-';
            }
            
            // Format tindakan: jika berupa ID, ambil nama dari Treatment
            $tindakanTeks = $referral->tindakan_dilakukan ?? $referral->medicalRecord?->tindakan ?? $referral->animalMedicalRecord?->tindakan;
            if ($tindakanTeks && preg_match('/^\d+(,\d+)*$/', $tindakanTeks)) {
                $tindakanIds = explode(',', $tindakanTeks);
                $treatments = \App\Models\Treatment::whereIn('id', $tindakanIds)->get();
                $tindakanTeks = $treatments->pluck('nama_tindakan')->join(', ');
            }
            
            $dokterNama = $referral->doctor?->name ?? $referral->medicalRecord?->doctor?->name ?? '-';
            
            // Kumpulkan semua obat dari kedua sistem
            $allPrescriptions = collect();
            
            // Dari AnimalMedicalRecord (prescriptions)
            if ($referral->animalMedicalRecord?->prescriptions) {
                $allPrescriptions = $allPrescriptions->merge($referral->animalMedicalRecord->prescriptions);
            }
            
            // Dari MedicalRecord (prescriptionItems)
            if ($referral->medicalRecord?->prescriptionItems) {
                foreach ($referral->medicalRecord->prescriptionItems as $item) {
                    $allPrescriptions->push((object)[
                        'medicine' => $item->medicine,
                        'jumlah' => $item->jumlah,
                        'instruksi' => $item->instruksi,
                    ]);
                }
            }
        @endphp

        {{-- Detail Card --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            {{-- Info Pasien --}}
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-sm font-semibold text-slate-500 uppercase mb-4">Informasi Pasien</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-500">Nama Hewan</p>
                        <p class="font-medium text-slate-800">{{ $namaHewan }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Jenis / Ras</p>
                        <p class="font-medium text-slate-800">{{ $jenis }} / {{ $ras }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Nama Pemilik</p>
                        <p class="font-medium text-slate-800">{{ $namaPemilik }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">No. HP</p>
                        <p class="font-medium text-slate-800">{{ $noHpPemilik }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Info Rujukan --}}
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-sm font-semibold text-slate-500 uppercase mb-4">Informasi Rujukan</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-500">Tujuan Rujukan</p>
                        <p class="font-medium text-slate-800">{{ $referral->tujuan_rujukan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Tanggal Rujukan</p>
                        <p class="font-medium text-slate-800">{{ $referral->tanggal_rujukan ? $referral->tanggal_rujukan->format('d F Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Dokter yang Merujuk</p>
                        <p class="font-medium text-slate-800">{{ $dokterNama }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Diagnosa & Alasan --}}
            <div class="p-6">
                <h3 class="text-sm font-semibold text-slate-500 uppercase mb-4">Detail Medis</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-500">Diagnosa Sementara</p>
                        <p class="text-slate-800">{{ $diagnosaTeks }}</p>
                    </div>
                    @if($tindakanTeks)
                    <div>
                        <p class="text-xs text-slate-500">Tindakan yang Sudah Dilakukan</p>
                        <p class="text-slate-800">{{ $tindakanTeks }}</p>
                    </div>
                    @endif
                    @if($allPrescriptions && $allPrescriptions->count() > 0)
                    <div>
                        <p class="text-xs text-slate-500 mb-2">Obat yang Sudah Diberikan</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($allPrescriptions as $prescription)
                                <li class="text-slate-800">
                                    <strong>{{ $prescription->medicine?->nama_obat ?? '-' }}</strong>
                                    @if($prescription->jumlah)
                                        - {{ $prescription->jumlah }} {{ $prescription->medicine?->satuan ?? '' }}
                                    @endif
                                    @if($prescription->instruksi)
                                        <br><span class="text-xs text-slate-600 italic ml-4">Instruksi: {{ $prescription->instruksi }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if($referral->alasan_rujukan)
                    <div>
                        <p class="text-xs text-slate-500">Alasan Rujukan</p>
                        <p class="text-slate-800">{{ $referral->alasan_rujukan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
