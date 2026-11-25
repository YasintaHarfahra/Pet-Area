@extends('layouts.dokter_app')

@section('title', 'Detail Antrian')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('dokter.antrian.index') }}" 
               class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Antrian #{{ $queue->queue_number }}</h1>
                <p class="text-gray-600">Informasi lengkap sebelum memulai pemeriksaan</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Info Hewan & Pemilik --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Informasi Pasien
                </h2>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    @if($queue->pet?->foto_hewan_url)
                    <img src="{{ $queue->pet->foto_hewan_url }}" alt="Foto {{ $queue->pet?->nama_hewan ?? 'Hewan' }}" class="w-16 h-16 rounded-full object-cover border border-gray-200">
                    @else
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-pink-600">{{ strtoupper(substr($queue->pet?->nama_hewan ?? 'P', 0, 1)) }}</span>
                    </div>
                    @endif
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $queue->pet?->nama_hewan ?? '-' }}</h3>
                        <p class="text-gray-600">{{ $queue->pet?->jenis ?? '-' }} - {{ $queue->pet?->ras ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Jenis Kelamin</p>
                        <p class="font-semibold text-gray-800">{{ $queue->pet?->jenis_kelamin ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Umur</p>
                        <p class="font-semibold text-gray-800">
                            @if($queue->pet?->tgl_lahir)
                                {{ $queue->pet->tgl_lahir->age }} tahun
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">No. Rekam Medis</p>
                        <p class="font-semibold text-gray-800">{{ $queue->pet?->no_rm ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Catatan</p>
                        <p class="font-semibold text-gray-800">{{ $queue->pet?->catatan ?? '-' }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t">
                    <h4 class="font-semibold text-gray-700 mb-3">Pemilik</h4>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $queue->pet?->owner?->nama ?? '-' }}</p>
                            <p class="text-sm text-gray-500">{{ $queue->pet?->owner?->no_hp ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Antrian & Keluhan --}}
        <div class="space-y-6">
            {{-- Status Antrian --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Status Antrian</h2>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700 font-bold text-2xl">
                        {{ $queue->queue_number }}
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nomor Antrian</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Tanggal: {{ $queue->queue_date ? $queue->queue_date->format('d/m/Y') : now()->format('d/m/Y') }}
                        </p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2
                            {{ $queue->status == 'waiting' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $queue->status == 'in-progress' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $queue->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        ">
                            {{ $queue->status == 'waiting' ? 'Menunggu' : '' }}
                            {{ $queue->status == 'in-progress' ? 'Sedang Diperiksa' : '' }}
                            {{ $queue->status == 'completed' ? 'Selesai' : '' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Keluhan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Keluhan / Catatan</h2>
                @if($queue->complaint)
                <div class="bg-yellow-50 rounded-lg p-4 text-gray-700">
                    {{ $queue->complaint }}
                </div>
                @else
                <p class="text-gray-500 italic">Tidak ada keluhan yang dicatat.</p>
                @endif
            </div>

            {{-- Riwayat Medis Terakhir --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Medis Terakhir (5 Terbaru)</h2>
                @if($queue->pet->medicalRecords && $queue->pet->medicalRecords->count() > 0)
                <div class="space-y-3">
                    @foreach($queue->pet->medicalRecords as $record)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">{{ Str::limit($record->diagnosis, 40) }}</p>
                            <p class="text-xs text-gray-500">{{ $record->examination_date ? $record->examination_date->format('d/m/Y') : '-' }}</p>
                        </div>
                        <!-- Link ke detail pemeriksaan di-nonaktifkan -->
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 italic">Belum ada riwayat medis.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    @if($queue->status == 'waiting')
    <div class="mt-6 flex gap-4">
        <form action="{{ route('dokter.antrian.start', $queue->id_queue) }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" 
                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Mulai Pemeriksaan
            </button>
        </form>
        <a href="{{ route('dokter.antrian.index') }}" 
           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">
            Kembali
        </a>
    </div>
    @endif
</div>
@endsection
