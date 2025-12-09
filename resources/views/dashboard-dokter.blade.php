@extends('layouts.dokter_app')

@section('title', 'Doctor Dashboard')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/20">
        <div class="p-6 fade-in">
            <div class="mb-8">
                <h1 class="text-3xl sm:text-4xl font-bold mb-2">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600">
                        Welcome Back!
                    </span>
                </h1>
                <p class="text-gray-600 text-base sm:text-lg">Here's your medical workspace, Dr. {{ Auth::user()->name }}.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Card: Total Patients --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Patients</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ App\Models\Patient::count() }}</h3>
                    <p class="text-green-600 text-sm font-medium mt-1">+{{ App\Models\Patient::where('created_at', '>=', now()->subWeek())->count() }} new this week</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card: Today's Queue --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Today's Queue</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ App\Models\Queue::whereDate('queue_date', today())->count() }}</h3>
                    <p class="text-orange-600 text-sm font-medium mt-1">{{ App\Models\Queue::whereDate('queue_date', today())->where('status', 'waiting')->count() }} waiting</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card: Medical Records --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Medical Records</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ App\Models\MedicalRecord::count() }}</h3>
                    <p class="text-blue-600 text-sm font-medium mt-1">{{ App\Models\MedicalRecord::where('updated_at', '>=', now()->startOfDay())->count() }} updated today</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card: Today's Appointments --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Today's Visits</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ App\Models\Visit::whereDate('created_at', today())->count() }}</h3>
                    <p class="text-purple-600 text-sm font-medium mt-1">{{ App\Models\Visit::whereDate('created_at', today())->where('status', 'pending')->count() }} pending</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
            </div>
        </div>
    </div>
</div>

            {{-- Menggunakan grid-cols-1 di layar kecil dan grid-cols-2 di layar besar untuk menempatkan Recent Patients dan Quick Actions berdampingan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Dibuat lebar seperti di gambar --}}
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Recent Patients</h3>
                        <a href="{{ route('dokter.patients.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View all</a>
                    </div>
                @php
                    $recentPatients = App\Models\Patient::latest()->take(5)->get();
                @endphp
                <div class="space-y-4">
                    @forelse($recentPatients as $patient)
                    <div class="flex items-center space-x-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ strtoupper(substr($patient->name,0,1)) }}{{ strtoupper(substr(explode(' ', $patient->name)[1] ?? '',0,1)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $patient->name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ $patient->species ?? 'Unknown Species' }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ optional($patient->created_at)->diffForHumans() }}</span>
                    </div>
                    @empty
                    {{-- Menggunakan card style yang lebih sederhana untuk meniru tampilan "No patients yet." di gambar --}}
                    <div class="flex flex-col items-center justify-center min-h-[150px] bg-white rounded-xl text-center text-gray-400 border border-dashed border-gray-200">
                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10h16V7H4zm0 0l-4 4m4-4l-4-4m16 4l4 4m-4-4l4-4"></path></svg>
                        <p class="font-medium">No patients yet.</p>
                        <p class="text-xs">Start by adding a new patient.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Dibuat lebar seperti di gambar --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Quick Actions</h3>
                </div>
                {{-- Mengubah tata letak Quick Actions menjadi 1 kolom dengan item yang lebih besar --}}
                <div class="grid grid-cols-2 gap-4"> 
                    {{-- Perubahan styling untuk meniru tampilan Quick Actions di gambar --}}
                    
                    {{-- New Record --}}
                    <a href="{{ route('dokter.medical_records.create') }}" class="flex flex-col items-center justify-center min-h-[100px] p-4 rounded-2xl transition-colors text-center border-2 border-blue-100 bg-blue-50/50 hover:bg-blue-100/70 space-y-2">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-md mb-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">New Record</span>
                    </a>
                    
                    {{-- View Queue --}}
                    <a href="{{ route('dokter.queues.index') }}" class="flex flex-col items-center justify-center min-h-[100px] p-4 rounded-2xl transition-colors text-center border-2 border-green-100 bg-green-50/50 hover:bg-green-100/70 space-y-2">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-md mb-2">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">View Queue</span>
                    </a>
                    
                    {{-- Patient List --}}
                    <a href="{{ route('dokter.patients.index') }}" class="flex flex-col items-center justify-center min-h-[100px] p-4 rounded-2xl transition-colors text-center border-2 border-purple-100 bg-purple-50/50 hover:bg-purple-100/70 space-y-2">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-md mb-2">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">Patient List</span>
                    </a>
                    
                    {{-- Medical Records (List) --}}
                    <a href="{{ route('dokter.medical_records.index') }}" class="flex flex-col items-center justify-center min-h-[100px] p-4 rounded-2xl transition-colors text-center border-2 border-indigo-100 bg-indigo-50/50 hover:bg-indigo-100/70 space-y-2">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-md mb-2">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">Medical Records</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
@endsection