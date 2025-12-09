@extends('layouts.dokter_app')

@section('title', 'Dashboard Dokter')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6 fade-in pb-8">

        {{-- Hero Section --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Selamat Datang, <span class="text-purple-600">Dr. {{ Auth::user()->name }}</span>
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Ringkasan aktivitas praktik Anda hari ini.
                </p>
            </div>
            <div class="px-4 py-2 bg-gray-50 text-gray-700 rounded-lg text-sm font-medium border border-gray-200 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 grid grid-cols-3 divide-x divide-gray-100">
            {{-- Status: Menunggu --}}
            <div class="p-4 lg:p-6 flex flex-col lg:flex-row items-center justify-between hover:bg-gray-50 first:rounded-l-2xl transition-colors group gap-2 lg:gap-0">
                <div class="text-center lg:text-left">
                    <p class="text-[10px] lg:text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Menunggu</p>
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 group-hover:text-orange-500 transition-colors">
                        {{ App\Models\Queue::whereDate('created_at', today())->where('status', 'waiting')->count() }}
                    </h3>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            {{-- Status: Selesai --}}
            <div class="p-4 lg:p-6 flex flex-col lg:flex-row items-center justify-between hover:bg-gray-50 transition-colors group gap-2 lg:gap-0">
                <div class="text-center lg:text-left">
                    <p class="text-[10px] lg:text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Selesai</p>
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 group-hover:text-green-500 transition-colors">
                        {{ App\Models\Queue::whereDate('created_at', today())->where('status', 'completed')->count() }}
                    </h3>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            {{-- Status: Total Pasien --}}
            <div class="p-4 lg:p-6 flex flex-col lg:flex-row items-center justify-between hover:bg-gray-50 last:rounded-r-2xl transition-colors group gap-2 lg:gap-0">
                <div class="text-center lg:text-left">
                    <p class="text-[10px] lg:text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pasien</p>
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 group-hover:text-blue-500 transition-colors">
                        {{ App\Models\Patient::count() }}
                    </h3>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left Column: Antrian Hari Ini --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden h-full flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h2 class="text-lg font-bold text-gray-900">Antrian Hari Ini</h2>
                        <a href="{{ route('dokter.queues.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline">
                            Lihat Semua
                        </a>
                    </div>
                    
                    <div class="p-4 flex-1">
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
                            <div class="flex items-center p-3 bg-white rounded-xl border border-gray-100 hover:border-blue-200 hover:shadow-sm transition-all group">
                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center font-bold text-blue-600 border border-blue-100 mr-3">
                                    {{ $queue->queue_number }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-gray-900 truncate">{{ $queue->patient->name }}</h4>
                                    <p class="text-xs text-gray-500 truncate">{{ $queue->patient->species }} • {{ $queue->patient->owner_name }}</p>
                                </div>
                                <div class="text-right mr-4 hidden sm:block">
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $queue->created_at->format('H:i') }}</span>
                                </div>
                                <a href="{{ route('dokter.medical_records.create', ['patient_id' => $queue->patient_id]) }}" class="px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded-lg hover:bg-blue-600 transition-colors">
                                    Periksa
                                </a>
                            </div>
                            @empty
                            <div class="text-center py-12">
                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Tidak ada antrian</p>
                                <p class="text-xs text-gray-500">Semua pasien telah ditangani.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Aksi Cepat --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm h-full flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-lg font-bold text-gray-900">Aksi Cepat</h2>
                    </div>
                    <div class="p-4 flex-1 space-y-3">
                        <a href="{{ route('dokter.medical_records.create') }}" class="flex items-center p-3 rounded-xl hover:bg-blue-50 border border-transparent hover:border-blue-100 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900 group-hover:text-blue-700">Buat Rekam Medis</span>
                                <span class="text-xs text-gray-500">Mulai pemeriksaan baru</span>
                            </div>
                        </a>
                        <a href="{{ route('dokter.patients.create') }}" class="flex items-center p-3 rounded-xl hover:bg-purple-50 border border-transparent hover:border-purple-100 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mr-3 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900 group-hover:text-purple-700">Tambah Pasien</span>
                                <span class="text-xs text-gray-500">Daftarkan pasien baru</span>
                            </div>
                        </a>
                        <a href="{{ route('dokter.reports.index') }}" class="flex items-center p-3 rounded-xl hover:bg-teal-50 border border-transparent hover:border-teal-100 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center mr-3 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900 group-hover:text-teal-700">Laporan & Arsip</span>
                                <span class="text-xs text-gray-500">Lihat riwayat data</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection