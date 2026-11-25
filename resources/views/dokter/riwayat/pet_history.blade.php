@extends('layouts.dokter_app')

@section('title', 'Riwayat Pasien - ' . ($pet->nama_hewan ?? 'Detail'))

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dokter.riwayat.index', ['mode' => 'pasien']) }}" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Pasien</h1>
    </div>

    {{-- Info Pasien --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-start gap-5">
            @if($pet->foto_hewan_url)
            <img src="{{ $pet->foto_hewan_url }}" alt="Foto {{ $pet->nama_hewan ?? 'Hewan' }}" class="w-16 h-16 rounded-full object-cover border border-gray-200 flex-shrink-0">
            @else
            <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-2xl font-bold text-pink-600">{{ strtoupper(substr($pet->nama_hewan ?? 'P', 0, 1)) }}</span>
            </div>
            @endif
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <h2 class="text-xl font-bold text-gray-800">{{ $pet->nama_hewan ?? '-' }}</h2>
                    @if($pet->trashed())
                        <span class="px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-700 rounded-full">Data Dihapus</span>
                    @endif
                </div>
                <p class="text-gray-500">{{ $pet->jenis ?? '-' }} {{ $pet->ras ? '- ' . $pet->ras : '' }}</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Jenis Kelamin</p>
                        <p class="font-medium text-gray-800">{{ $pet->jenis_kelamin ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">No. RM</p>
                        <p class="font-medium text-gray-800">{{ $pet->no_rm ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Pemilik</p>
                        <p class="font-medium text-gray-800">{{ $pet->owner?->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">No. HP Pemilik</p>
                        <p class="font-medium text-gray-800">{{ $pet->owner?->no_hp ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Rekam Medis --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Rekam Medis ({{ $medicalRecords->count() }})</h3>
        </div>

        @forelse($medicalRecords as $record)
        <div class="p-6 border-b border-gray-100 hover:bg-gray-50 transition">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-sm font-medium text-gray-500">
                            {{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y, H:i') }}
                        </span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-pink-100 text-pink-700 font-medium">
                            Dr. {{ $record->doctor->name ?? '-' }}
                        </span>
                    </div>

                    {{-- Diagnosis --}}
                    <div class="mb-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Diagnosis</p>
                        <p class="text-gray-800 font-medium">{{ $record->diagnosis ?? $record->diagnosa ?? '-' }}</p>
                    </div>

                    {{-- Gejala --}}
                    @if($record->symptoms || $record->keluhan)
                    <div class="mb-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Gejala / Keluhan</p>
                        <p class="text-gray-600 text-sm">{{ $record->symptoms ?? $record->keluhan ?? '-' }}</p>
                    </div>
                    @endif

                    {{-- Vital Signs --}}
                    <div class="flex gap-4 text-sm">
                        @if($record->berat_badan)
                        <span class="text-gray-500">BB: <span class="font-medium text-gray-700">{{ $record->berat_badan }} kg</span></span>
                        @endif
                        @if($record->suhu_tubuh)
                        <span class="text-gray-500">Suhu: <span class="font-medium text-gray-700">{{ $record->suhu_tubuh }} °C</span></span>
                        @endif
                    </div>

                    {{-- Tindakan --}}
                    @if($record->treatments->count())
                    <div class="mt-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Tindakan</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($record->treatments as $treatment)
                            <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-full font-medium">
                                {{ $treatment->nama_tindakan }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Resep --}}
                    @if($record->prescriptionItems->count())
                    <div class="mt-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Resep Obat</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($record->prescriptionItems as $item)
                            <span class="px-2 py-1 bg-purple-50 text-purple-700 text-xs rounded-full font-medium">
                                {{ $item->medicine?->nama_obat ?? 'Obat dihapus' }} × {{ $item->jumlah }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <a href="{{ route('dokter.riwayat.record', $record->id_medical_record) }}" 
                   class="inline-flex items-center gap-1 px-4 py-2 bg-pink-50 text-pink-600 hover:bg-pink-100 rounded-lg font-medium text-sm transition flex-shrink-0">
                    Detail
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Rekam Medis</h3>
            <p class="text-gray-500">Hewan ini belum memiliki riwayat pemeriksaan.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
