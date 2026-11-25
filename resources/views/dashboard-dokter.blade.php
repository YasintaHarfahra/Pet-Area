@extends('layouts.dokter_app')

@section('title', 'Dashboard Dokter')

@section('content')
    <div class="max-w-7xl mx-auto px-4 space-y-6 fade-in pb-8">

        {{-- Hero Section --}}
        <div class="card-premium p-6 md:p-8 border-l-4 border-[#970747]">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        Selamat Datang, <span class="text-[#970747]">Dr. {{ Auth::user()->name }}</span>
                    </h1>
                    <p class="text-gray-600 text-sm mt-2">
                        Ringkasan aktivitas praktik Anda hari ini
                    </p>
                </div>
                <div class="px-4 py-2 bg-gray-50 text-gray-700 rounded-xl text-sm font-semibold border border-gray-200 flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Status: Menunggu --}}
            <div class="card-elevated p-6 border-l-4 border-orange-500 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Menunggu</p>
                        <h3 class="text-4xl font-bold text-gray-900">
                            {{ App\Models\Queue::whereDate('created_at', today())->where('status', 'waiting')->count() }}
                        </h3>
                        <p class="text-orange-600 text-xs font-semibold mt-2">Pasien antrian</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-100 to-orange-50 rounded-2xl flex items-center justify-center text-orange-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            {{-- Status: Selesai --}}
            <div class="card-elevated p-6 border-l-4 border-green-500 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Selesai</p>
                        <h3 class="text-4xl font-bold text-gray-900">
                            {{ App\Models\Queue::whereDate('created_at', today())->where('status', 'completed')->count() }}
                        </h3>
                        <p class="text-green-600 text-xs font-semibold mt-2">Pasien ditangani</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-50 rounded-2xl flex items-center justify-center text-green-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            {{-- Status: Total Pasien --}}
            <div class="card-elevated p-6 border-l-4 border-[#970747] hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Total Pasien</p>
                        <h3 class="text-4xl font-bold text-gray-900">
                            {{ App\Models\Patient::count() }}
                        </h3>
                        <p class="text-[#970747] text-xs font-semibold mt-2">Dalam database</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-pink-100 to-pink-50 rounded-2xl flex items-center justify-center text-[#970747]">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left Column: Antrian Hari Ini --}}
            <div class="lg:col-span-2">
                <div class="card-premium overflow-hidden flex flex-col h-full">
                    <div class="px-6 py-4 border-b-2 border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white">
                        <h2 class="text-lg font-bold text-gray-900">Antrian Hari Ini</h2>
                        <a href="{{ route('dokter.antrian.index') }}" class="text-sm font-semibold text-[#970747] hover:text-[#7a0538] hover:bg-pink-50 px-3 py-1 rounded-lg transition">
                            Lihat Semua →
                        </a>
                    </div>
                    
                    <div class="p-6 flex-1">
                        @php 
                            $recentWaitingQueues = App\Models\Queue::whereDate('created_at', today())
                                ->where('status', 'waiting')
                                ->with('patient')
                                ->orderBy('queue_number', 'asc')
                                ->take(5)
                                ->get(); 
                        @endphp

                        <div class="space-y-3">
                            @forelse($recentWaitingQueues as $queue)
                            <div class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-pink-300 hover:bg-pink-50 transition-all group">
                                <div class="w-12 h-12 bg-gradient-to-br from-[#970747] to-[#970747] rounded-xl flex items-center justify-center font-bold text-white text-sm mr-3 flex-shrink-0 shadow-sm">
                                    {{ $queue->queue_number }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $queue->patient->name }}</h4>
                                    <p class="text-xs text-gray-500 truncate">{{ $queue->patient->species }} • {{ $queue->patient->owner_name }}</p>
                                </div>
                                <div class="text-right mr-3">
                                    <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">{{ $queue->created_at->format('H:i') }}</span>
                                </div>
                                <a href="{{ route('dokter.medical_records.create', ['patient_id' => $queue->patient_id]) }}" class="px-4 py-2 bg-gradient-to-r from-[#970747] to-[#7a0538] text-white text-xs font-semibold rounded-lg hover:from-[#7a0538] hover:to-[#7a0538] transition-all shadow-sm hover:shadow-md-enhanced whitespace-nowrap flex-shrink-0">
                                    Periksa
                                </a>
                            </div>
                            @empty
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">Tidak ada antrian</p>
                                <p class="text-xs text-gray-500 mt-1">Semua pasien telah ditangani.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Aksi Cepat --}}
            <div class="lg:col-span-1">
                <div class="card-premium overflow-hidden flex flex-col h-full">
                    <div class="px-6 py-4 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h2 class="text-lg font-bold text-gray-900">Aksi Cepat</h2>
                    </div>
                    <div class="p-6 flex-1 space-y-3">
                        <a href="{{ route('dokter.medical_records.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-pink-50 border border-gray-200 hover:border-pink-300 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-pink-100 text-[#970747] flex items-center justify-center group-hover:bg-[#970747] group-hover:text-white transition-colors flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-semibold text-gray-900 group-hover:text-[#7a0538]">Buat Rekam Medis</span>
                                <span class="text-xs text-gray-500">Mulai pemeriksaan</span>
                            </div>
                        </a>
                        <a href="{{ route('dokter.patients.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-pink-50 border border-gray-200 hover:border-pink-300 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-pink-100 text-[#970747] flex items-center justify-center group-hover:bg-[#970747] group-hover:text-white transition-colors flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-semibold text-gray-900 group-hover:text-[#970747]">Tambah Pasien</span>
                                <span class="text-xs text-gray-500">Daftarkan baru</span>
                            </div>
                        </a>
                        <a href="{{ route('dokter.reports.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-teal-50 border border-gray-200 hover:border-teal-300 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition-colors flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-semibold text-gray-900 group-hover:text-teal-700">Laporan & Arsip</span>
                                <span class="text-xs text-gray-500">Lihat riwayat data</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Follow-up Reminders Section --}}
        @if(isset($followUpReminders) && ($followUpReminders->count() > 0 || $overdueFollowUps > 0))
        <div class="card-premium overflow-hidden">
            <div class="px-6 py-4 border-b-2 border-gray-100 flex items-center justify-between bg-gradient-to-r from-orange-50 to-amber-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Pengingat Kontrol Lanjutan</h2>
                        <p class="text-xs text-gray-600">Pasien yang perlu kontrol dalam 7 hari ke depan</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($overdueFollowUps > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                            ⚠ {{ $overdueFollowUps }} Terlambat
                        </span>
                    @endif
                    @if($upcomingFollowUps > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700 border border-orange-200">
                            📅 {{ $upcomingFollowUps }} Akan Datang
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="p-6">
                @if($followUpReminders->count() > 0)
                    <div class="space-y-3">
                        @foreach($followUpReminders as $reminder)
                            @php
                                $statusLabel = $reminder->getFollowUpStatusLabel();
                                $colorClasses = [
                                    'red' => 'bg-red-100 text-red-700 border-red-200',
                                    'orange' => 'bg-orange-100 text-orange-700 border-orange-200',
                                    'yellow' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    'blue' => 'bg-pink-100 text-[#7a0538] border-pink-200',
                                    'green' => 'bg-green-100 text-green-700 border-green-200',
                                    'gray' => 'bg-gray-100 text-gray-700 border-gray-200',
                                ];
                                $badgeClass = $colorClasses[$statusLabel['color']] ?? $colorClasses['gray'];
                            @endphp
                            <div class="flex items-center p-4 rounded-xl border {{ $reminder->isOverdue() ? 'border-red-200 bg-red-50' : 'border-gray-200 hover:border-orange-300 hover:bg-orange-50' }} transition-all group">
                                <div class="w-12 h-12 bg-gradient-to-br {{ $reminder->isOverdue() ? 'from-red-500 to-red-600' : 'from-orange-500 to-amber-500' }} rounded-xl flex items-center justify-center font-bold text-white text-sm mr-3 flex-shrink-0 shadow-sm">
                                    {{ substr($reminder->patient->name ?? '?', 0, 2) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $reminder->patient->name ?? '-' }}</h4>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $reminder->patient->species ?? '-' }} • {{ $reminder->patient->owner_name ?? '-' }}
                                        @if($reminder->follow_up_notes)
                                            <span class="text-orange-600">• {{ $reminder->follow_up_notes }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right mr-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold {{ $badgeClass }} border">
                                        {{ $statusLabel['icon'] }} {{ $statusLabel['label'] }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $reminder->next_control_date ? $reminder->next_control_date->format('d M Y') : '-' }}
                                    </p>
                                </div>
                                <a href="{{ route('patients.show', $reminder->patient_id) }}" class="px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white text-xs font-semibold rounded-lg hover:from-orange-600 hover:to-amber-600 transition-all shadow-sm hover:shadow-md whitespace-nowrap flex-shrink-0">
                                    Lihat
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-3 text-green-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">Tidak ada jadwal kontrol</p>
                        <p class="text-xs text-gray-500 mt-1">Semua pasien sudah terjadwal dengan baik</p>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- ================================ --}}
        {{-- TOP 5 DIAGNOSA TERBANYAK (CHART) --}}
        {{-- ================================ --}}
        @if(isset($topDiagnoses) && count($topDiagnoses) > 0)
        <div class="card-premium overflow-hidden">
            <div class="px-6 py-4 border-b-2 border-gray-100 flex items-center justify-between bg-gradient-to-r from-pink-50 to-pink-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#970747] to-purple-500 rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Top 5 Diagnosa Terbanyak</h2>
                        <p class="text-xs text-gray-600">Statistik penyakit yang paling sering ditangani</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    @php $maxCount = $topDiagnoses->max('jumlah') ?: 1; @endphp
                    @foreach($topDiagnoses as $index => $diagnosa)
                        @php
                            $percentage = ($diagnosa->jumlah / $maxCount) * 100;
                            $colors = [
                                'from-[#970747] to-[#970747]',
                                'from-pink-500 to-pink-600',
                                'from-[#970747] to-[#970747]',
                                'from-teal-500 to-teal-600',
                                'from-emerald-500 to-emerald-600',
                            ];
                            $bgColors = [
                                'bg-pink-100',
                                'bg-pink-100',
                                'bg-pink-100',
                                'bg-teal-100',
                                'bg-emerald-100',
                            ];
                        @endphp
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-lg {{ $bgColors[$index] ?? 'bg-gray-100' }} flex items-center justify-center font-bold text-gray-700 text-sm flex-shrink-0">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-800 truncate">
                                        {{ $diagnosa->diagnosa ?: 'Belum ada diagnosa' }}
                                    </span>
                                    <span class="text-sm font-bold text-gray-700 flex-shrink-0 ml-2">
                                        {{ $diagnosa->jumlah }} kasus
                                    </span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="h-2.5 rounded-full bg-gradient-to-r {{ $colors[$index] ?? 'from-gray-400 to-gray-500' }}" 
                                         data-width="{{ $percentage }}" style="width: var(--bar-width)"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        Data berdasarkan rekam medis yang tersimpan
                    </p>
                    <a href="{{ route('animal-records.index') }}" class="text-sm font-semibold text-[#970747] hover:text-[#7a0538] hover:bg-pink-50 px-3 py-1 rounded-lg transition">
                        Lihat Semua Rekam Medis →
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-width]').forEach(el => {
        el.style.width = el.dataset.width + '%';
    });
</script>
@endpush