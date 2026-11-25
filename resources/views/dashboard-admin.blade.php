@extends('layouts.admin_app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="max-w-7xl mx-auto px-4 pb-12">
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-heading-1 mb-2">Welcome Back!</h1>
            <p class="text-gray-600">Ringkasan aktivitas dan statistik Pet Area Clinic hari ini</p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Card: Total Pasien --}}
            <div class="card-elevated p-6 border-l-4 border-[#970747] hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Total Pasien</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $totalPatients }}</h3>
                        <p class="text-green-600 text-xs font-semibold mt-2">↑ 12% dari bulan lalu</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-pink-100 to-pink-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#970747]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card: Monthly Revenue --}}
            <div class="card-elevated p-6 border-l-4 border-green-500 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Pendapatan Bulanan</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $formattedMonthlyRevenue }}</h3>
                        <p class="text-green-600 text-xs font-semibold mt-2">↑ 8% dari bulan lalu</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card: Today's Appointments --}}
            <div class="card-elevated p-6 border-l-4 border-[#970747] hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Kunjungan Hari Ini</p>
                        <h3 class="text-3xl font-bold text-gray-900">24</h3>
                        <p class="text-[#970747] text-xs font-semibold mt-2">3 menunggu</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-100 to-purple-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card: Active Staff --}}
            <div class="card-elevated p-6 border-l-4 border-orange-500 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Staff Aktif</p>
                        <h3 class="text-3xl font-bold text-gray-900">8</h3>
                        <p class="text-gray-600 text-xs font-semibold mt-2">Semua online</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-100 to-orange-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Recent Patients Section --}}
            <div class="lg:col-span-2">
                <div class="card-premium p-6">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Pasien Terbaru</h3>
                        <a href="{{ route('admin.patients.index') }}" class="text-sm font-semibold text-[#970747] hover:text-[#7a0538] hover:bg-pink-50 px-3 py-1 rounded-lg transition">Lihat Semua →</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($recentPatients ?? [] as $patient)
                            @php
                                $colors = ['blue', 'green', 'purple', 'pink', 'indigo'];
                                $color = $colors[$loop->index % count($colors)];
                            @endphp
                            <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-{{ $color }}-50 transition-all border border-gray-100 hover:border-{{ $color }}-200">
                                <div class="w-12 h-12 bg-gradient-to-br from-{{ $color }}-400 to-{{ $color }}-600 rounded-xl flex items-center justify-center flex-shrink-0 text-white font-bold text-sm">
                                    {{ strtoupper(substr($patient->name, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900">{{ $patient->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $patient->species ?? '-' }} • {{ $patient->breed ?? '-' }}</p>
                                </div>
                                <span class="text-xs text-gray-400 whitespace-nowrap">{{ $patient->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>Belum ada data pasien</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Quick Actions Section --}}
            <div>
                <div class="card-premium p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 pb-4 border-b-2 border-gray-100">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.patients.create') }}" 
                           class="flex items-center gap-3 p-3 rounded-xl hover:bg-pink-50 border border-gray-200 hover:border-pink-300 transition-all group">
                            <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center text-[#970747] group-hover:bg-[#970747] group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-gray-900">Tambah Pasien</span>
                                <span class="text-xs text-gray-500">Pasien baru</span>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.transactions.create') }}" 
                           class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50 border border-gray-200 hover:border-green-300 transition-all group">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-gray-900">Transaksi Baru</span>
                                <span class="text-xs text-gray-500">Catat pembayaran</span>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.visits.create') }}" 
                           class="flex items-center gap-3 p-3 rounded-xl hover:bg-purple-50 border border-gray-200 hover:border-purple-300 transition-all group">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-gray-900">Jadwal Kunjungan</span>
                                <span class="text-xs text-gray-500">Buat kunjungan baru</span>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.queues.create') }}" 
                           class="flex items-center gap-3 p-3 rounded-xl hover:bg-pink-50 border border-gray-200 hover:border-pink-300 transition-all group">
                            <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center text-[#970747] group-hover:bg-[#970747] group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-gray-900">Tambah Antrian</span>
                                <span class="text-xs text-gray-500">Pasien menunggu</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.medical_records.index') }}" 
                           class="flex items-center gap-3 p-3 rounded-xl hover:bg-orange-50 border border-gray-200 hover:border-orange-300 transition-all group">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-gray-900">Rekam Medis</span>
                                <span class="text-xs text-gray-500">Lihat data medis</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Follow-up Reminders Section --}}
        @if(isset($followUpReminders) && ($followUpReminders->count() > 0 || $overdueFollowUps > 0))
        <div class="card-premium overflow-hidden mt-6">
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
    </div>
@endsection