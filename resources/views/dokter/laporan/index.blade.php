@extends('layouts.dokter_app')

@section('title', 'Laporan Medis')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Medis</h1>
        <p class="text-gray-600">Download laporan rekam medis dalam format Excel atau PDF</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pasien</p>
                    <p class="text-3xl font-black text-gray-800">{{ $stats['total_pasien'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-50 text-gray-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kucing</p>
                    <p class="text-3xl font-black text-blue-800">{{ $stats['total_kucing'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Anjing</p>
                    <p class="text-3xl font-black text-green-800">{{ $stats['total_anjing'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kasus Terbanyak</p>
                    <p class="text-lg font-black text-[#970747]">{{ $stats['diagnosa_terbanyak'] ? ucfirst($stats['diagnosa_terbanyak']) : '-' }}</p>
                    <p class="text-xs text-gray-500">{{ $stats['jumlah_diagnosa_terbanyak'] ? ($stats['jumlah_diagnosa_terbanyak'] . ' kasus') : '' }}</p>
                </div>
                <div class="w-12 h-12 bg-pink-50 text-[#970747] rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Download Card --}}
    <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 p-8 md:p-10">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center text-[#970747]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Pilih Kriteria Laporan</h2>
        </div>

        <form action="{{ route('dokter.laporan.index') }}" method="GET" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:bg-white transition-all outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:bg-white transition-all outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Diagnosa</label>
                    <input type="text" name="diagnosis" value="{{ request('diagnosis') }}" placeholder="Contoh: Parvovirus"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:bg-white transition-all outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nama Hewan</label>
                    <input type="text" name="pet_name" value="{{ request('pet_name') }}" placeholder="Contoh: Milo"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:bg-white transition-all outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Dokter</label>
                    <select name="doctor_id"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:bg-white transition-all outline-none">
                        <option value="all" {{ request('doctor_id', 'all') === 'all' ? 'selected' : '' }}>Semua Dokter</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id_user }}" {{ (string) request('doctor_id') === (string) $doctor->id_user ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-4 pt-6 border-t border-gray-50">
                        <div class="flex flex-row items-center justify-center gap-3 mb-4">
                            <button type="submit" class="px-8 py-3 bg-[#970747] text-white rounded-xl font-bold hover:bg-[#7a0538] transition active:scale-95 text-base">
                                Update Preview Data
                            </button>
                            <a href="{{ route('dokter.laporan.index') }}" class="px-8 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition text-base text-center">
                                Reset
                            </a>
                        </div>
                </div>
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex gap-3 w-full md:w-auto">
                        <a href="{{ route('dokter.laporan.excel', request()->query()) }}" 
                           class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-8 py-3 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition shadow-lg shadow-green-100 active:scale-95 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Excel
                        </a>
                        <a href="{{ route('dokter.laporan.pdf', request()->query()) }}" 
                           class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-8 py-3 bg-[#970747] text-white rounded-xl font-bold hover:bg-[#7a0538] transition shadow-lg shadow-pink-100 active:scale-95 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    {{-- Info Section --}}
    <div class="mt-8 p-6 bg-blue-50 rounded-2xl flex items-start gap-4 border border-blue-100">
        <svg class="w-6 h-6 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <p class="text-sm font-bold text-blue-900 mb-1">Catatan Laporan</p>
            <p class="text-xs text-blue-700 leading-relaxed">
                Gunakan tombol <strong>Update Preview Data</strong> di atas untuk memperbarui jumlah total data sebelum menekan tombol download. Pastikan rentang tanggal sudah benar untuk mendapatkan akurasi laporan yang tepat.
            </p>
        </div>
    </div>
</div>

@endsection