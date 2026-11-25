@extends('layouts.dokter_app')

@section('title', 'Dashboard Dokter - Pet Area Clinic')

@section('content')
<div class="max-w-7xl mx-auto px-4 space-y-4 fade-in pb-6">

    {{-- Greeting --}}
    <div class="bg-white rounded-xl border border-gray-100 px-5 py-3 flex items-center justify-between" style="box-shadow: 0 1px 8px rgba(0,0,0,0.05);">
        <div>
            <h1 class="text-base font-bold text-gray-800">Halo, <span class="text-[#970747]">{{ Auth::guard('dokter')->user()->name }}</span></h1>
            <p class="text-xs text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    {{-- Stats Row - 3 cards horizontal --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-xl border border-gray-100 px-4 py-3 flex items-center justify-between" style="box-shadow: 0 1px 8px rgba(0,0,0,0.05);">
            <div>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Periksa Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pemeriksaan_hari_ini'] ?? 0 }}</p>
            </div>
            <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 px-4 py-3 flex items-center justify-between" style="box-shadow: 0 1px 8px rgba(0,0,0,0.05);">
            <div>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Total Pasien</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_pasien'] ?? 0 }}</p>
            </div>
            <div class="w-8 h-8 bg-pink-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-[#970747]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 px-4 py-3 flex items-center justify-between" style="box-shadow: 0 1px 8px rgba(0,0,0,0.05);">
            <div>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Kontrol Mendatang</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['kontrol_mendatang'] ?? 0 }}</p>
            </div>
            <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Main Content: 3 columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Antrian Hari Ini --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden flex flex-col" style="box-shadow: 0 1px 8px rgba(0,0,0,0.05);">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-800">Antrian Hari Ini</h2>
                <a href="{{ route('dokter.antrian.index') }}" class="text-xs text-[#970747] font-medium hover:underline">Semua &rarr;</a>
            </div>
            @if($todayQueues->count() > 0)
            <div class="divide-y divide-gray-50 flex-1">
                @foreach($todayQueues->take(4) as $queue)
                <div class="px-4 py-2.5 flex items-center justify-between hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center text-xs font-bold
                            {{ $queue->status === 'in_progress' ? 'bg-[#970747] text-white' : ($queue->status === 'completed' ? 'bg-green-100 text-green-700' : ($queue->status === 'waiting' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-400')) }}">
                            {{ $queue->queue_number }}
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-800">{{ $queue->pet->nama_hewan ?? '-' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $queue->pet->owner->nama_pemilik ?? $queue->pet->owner->nama ?? '-' }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                        {{ $queue->status === 'in_progress' ? 'bg-pink-50 text-[#970747]' : ($queue->status === 'completed' ? 'bg-green-50 text-green-600' : ($queue->status === 'waiting' ? 'bg-amber-50 text-amber-600' : 'bg-gray-50 text-gray-400')) }}">
                        {{ $queue->status === 'waiting' ? 'Menunggu' : ($queue->status === 'in_progress' ? 'Diperiksa' : ($queue->status === 'completed' ? 'Selesai' : ucfirst(str_replace('_', ' ', $queue->status)))) }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-4 py-8 text-center flex-1 flex items-center justify-center">
                <p class="text-xs text-gray-400">Belum ada antrian</p>
            </div>
            @endif
        </div>

        {{-- Rekam Medis Terbaru --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden flex flex-col" style="box-shadow: 0 1px 8px rgba(0,0,0,0.05);">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-800">Rekam Medis Terbaru</h2>
                <a href="{{ route('dokter.riwayat.index') }}" class="text-xs text-[#970747] font-medium hover:underline">Semua &rarr;</a>
            </div>
            @if($recentRecords->count() > 0)
            <div class="divide-y divide-gray-50 flex-1">
                @foreach($recentRecords->take(4) as $record)
                <a href="{{ route('dokter.riwayat.record', $record->id_medical_record) }}" class="block px-4 py-2.5 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-800">{{ $record->pet->nama_hewan ?? '-' }}</p>
                            <p class="text-[11px] text-gray-400 truncate">{{ $record->diagnosis ?? 'Belum ada diagnosa' }}</p>
                        </div>
                        <p class="text-[11px] text-gray-400 ml-3 whitespace-nowrap">{{ $record->visit_date?->format('d/m/y') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="px-4 py-8 text-center flex-1 flex items-center justify-center">
                <p class="text-xs text-gray-400">Belum ada rekam medis</p>
            </div>
            @endif
        </div>

        {{-- Kontrol & Diagnosa --}}
        <div class="space-y-4">
            {{-- Kontrol Mendatang --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden" style="box-shadow: 0 1px 8px rgba(0,0,0,0.05);">
                <div class="px-4 py-3 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-800">Kontrol 7 Hari</h2>
                </div>
                @if($kontrolReminders->count() > 0)
                <div class="divide-y divide-gray-50">
                    @foreach($kontrolReminders->take(3) as $reminder)
                    <div class="px-4 py-2.5 flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-800">{{ $reminder->pet->nama_hewan ?? '-' }}</p>
                            <p class="text-[11px] text-gray-400 truncate">{{ $reminder->pet->owner->nama_pemilik ?? $reminder->pet->owner->nama ?? '-' }}</p>
                        </div>
                        <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full ml-2 whitespace-nowrap">
                            {{ $reminder->next_control_date?->format('d M') }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-4 py-5 text-center">
                    <p class="text-xs text-gray-400">Tidak ada kontrol</p>
                </div>
                @endif
            </div>

            {{-- Top Diagnosa --}}
            @if($topDiagnoses->count() > 0)
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden" style="box-shadow: 0 1px 8px rgba(0,0,0,0.05);">
                <div class="px-4 py-3 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-800">Top Diagnosa</h2>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($topDiagnoses->take(3) as $index => $diagnosa)
                    <div class="px-4 py-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-pink-50 flex items-center justify-center text-[#970747] font-bold text-[10px]">{{ $index + 1 }}</span>
                        <p class="text-xs text-gray-700 flex-1 truncate">{{ ucfirst($diagnosa->diagnosis) }}</p>
                        <span class="text-[10px] font-semibold text-gray-400">{{ $diagnosa->total }} kasus</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
