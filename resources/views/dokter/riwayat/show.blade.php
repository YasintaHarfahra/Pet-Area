                {{-- Prescription / Resep Obat --}}
                <div class="bg-purple-50 px-6 py-3 border-b border-purple-100">
                    <h2 class="font-semibold text-purple-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        Resep Obat
                    </h2>
                </div>
                <div class="p-6">
                    @if($record->prescriptionItems && $record->prescriptionItems->count())
                        <ul class="list-decimal pl-5">
                            @foreach($record->prescriptionItems as $item)
                                <li>
                                    {{ $item->medicine->nama_obat ?? '-' }}
                                    <span class="text-xs text-gray-500">({{ $item->jumlah ?? $item->qty ?? '-' }} {{ $item->medicine->satuan ?? '-' }}, {{ $item->instruksi ?? $item->instructions ?? '-' }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-800 whitespace-pre-line">-</p>
                    @endif
                </div>

                {{-- Referral / Rujukan --}}
                <div class="bg-orange-50 px-6 py-3 border-b border-orange-100">
                    <h2 class="font-semibold text-orange-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path>
                        </svg>
                        Surat Rujukan
                    </h2>
                </div>
                <div class="p-6">
                    @if($record->referral)
                        <div class="mb-2"><span class="font-semibold">Tujuan:</span> {{ $record->referral->tujuan_rujukan }}</div>
                        <div class="mb-2"><span class="font-semibold">Alasan:</span> {{ $record->referral->alasan_rujukan }}</div>
                    @else
                        <p class="text-gray-800 whitespace-pre-line">-</p>
                    @endif
                </div>

                {{-- Next Control / Rencana Kontrol --}}
                <div class="bg-amber-50 px-6 py-3 border-b border-amber-100">
                    <h2 class="font-semibold text-amber-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path>
                        </svg>
                        Rencana Kontrol Berikutnya
                    </h2>
                </div>
                <div class="p-6">
                    @if($record->next_control_date)
                        <div class="mb-2"><span class="font-semibold">Tanggal:</span> {{ \Carbon\Carbon::parse($record->next_control_date)->format('d F Y') }}</div>
                        <div class="mb-2"><span class="font-semibold">Catatan:</span> {{ $record->follow_up_notes ?? '-' }}</div>
                    @else
                        <p class="text-gray-800 whitespace-pre-line">-</p>
                    @endif
                </div>
@extends('layouts.dokter_app')

@section('title', 'Riwayat ' . $pet->nama_hewan)

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('dokter.riwayat.index') }}" 
               class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Riwayat Medis: {{ $pet->nama_hewan }}</h1>
                <p class="text-gray-600">{{ $pet->jenis }} - {{ $pet->ras ?? 'Tidak diketahui' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Pet Info --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4 text-center">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-3xl font-bold text-white">{{ strtoupper(substr($pet->nama_hewan, 0, 1)) }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-white">{{ $pet->nama_hewan }}</h2>
                    <p class="text-pink-100">{{ $pet->jenis }}</p>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-500">Ras</span>
                        <span class="font-medium text-gray-800">{{ $pet->ras ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-500">Jenis Kelamin</span>
                        <span class="font-medium text-gray-800">{{ $pet->jenis_kelamin ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-500">Tanggal Lahir</span>
                        <span class="font-medium text-gray-800">
                            {{ $pet->tgl_lahir ? $pet->tgl_lahir->format('d/m/Y') : '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-500">No. Rekam Medis</span>
                        <span class="font-medium text-gray-800">{{ $pet->no_rm ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-500">Catatan</span>
                        <span class="font-medium text-gray-800">{{ $pet->catatan ?? '-' }}</span>
                    </div>

                    {{-- Owner Info --}}
                    <div class="pt-4 border-t">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Pemilik</h3>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $pet->owner->nama ?? '-' }}</p>
                                <p class="text-sm text-gray-500">{{ $pet->owner->no_hp ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Medical Records --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800">Daftar Rekam Medis</h2>
                    <p class="text-sm text-gray-500">Total {{ $medicalRecords->total() }} rekam medis</p>
                </div>

                @if($medicalRecords->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($medicalRecords as $record)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                        {{ $record->examination_date ? $record->examination_date->format('d/m/Y') : '-' }}
                                    </span>
                                    @if($record->doctor)
                                    <span class="text-xs text-gray-500">
                                        oleh Dr. {{ $record->doctor->name }}
                                    </span>
                                    @endif
                                </div>

                                <h3 class="font-semibold text-gray-800 mb-1">{{ $record->diagnosis ?? 'Diagnosis tidak tersedia' }}</h3>
                                
                                @if($record->symptoms)
                                <p class="text-sm text-gray-600 mb-2">
                                    <strong>Gejala:</strong> {{ Str::limit($record->symptoms, 150) }}
                                </p>
                                @endif

                                <div class="bg-green-50 px-6 py-3 border-b border-green-100">
                                    <h2 class="font-semibold text-green-800 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                        </svg>
                                        Penanganan / Treatment
                                    </h2>
                                </div>
                                <div class="p-6">
                                    @if($record->treatments && $record->treatments->count())
                                        <ul class="list-disc pl-5">
                                            @foreach($record->treatments as $tindakan)
                                                <li>{{ $tindakan->nama_tindakan }} <span class="text-xs text-gray-500">({{ $tindakan->kategori ?? '-' }})</span></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-800 whitespace-pre-line">-</p>
                                    @endif
                                </div>

                                @if($record->prescriptions && $record->prescriptions->count() > 0)
                                <div class="mt-3">
                                    <p class="text-xs font-medium text-gray-500 mb-1">Resep Obat:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($record->prescriptions->take(3) as $prescription)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700">
                                            {{ $prescription->medicine->name ?? '-' }}
                                        </span>
                                        @endforeach
                                        @if($record->prescriptions->count() > 3)
                                        <span class="text-xs text-gray-500">+{{ $record->prescriptions->count() - 3 }} lainnya</span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>

                            <a href="{{ route('dokter.riwayat.record', $record->id_medical_record) }}" 
                               class="ml-4 p-2 text-gray-400 hover:text-pink-600 hover:bg-pink-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $medicalRecords->links() }}
                </div>

                @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Rekam Medis</h3>
                    <p class="text-gray-500">Hewan ini belum memiliki riwayat pemeriksaan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
