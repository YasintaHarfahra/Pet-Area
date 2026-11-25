@extends('layouts.admin_app')

@section('title', 'Riwayat Kunjungan - Admin')
@section('header_title', 'Riwayat Kunjungan')

@section('content')
    <div class="max-w-7xl mx-auto px-4 pb-12">
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <h1 class="text-heading-1 mb-2">Daftar Riwayat Kunjungan</h1>
                    <p class="text-gray-600">Kelola dan pantau semua riwayat kunjungan pasien ke klinik</p>
                </div>
                <a href="{{ route('admin.visits.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#970747] to-[#970747] text-white rounded-xl font-semibold shadow-lg hover:shadow-lg-enhanced hover:from-[#970747] hover:to-[#7a0538] transition-all hover-lift">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Catat Kunjungan Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-lg mb-6 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Table Section --}}
        <div class="card-premium overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal Kunjungan</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Pasien</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Pemilik</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Tujuan Kunjungan</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Status</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Admin Pencatat</span>
                            </th>
                            <th class="px-6 py-4 text-center">
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse ($visits as $visit)
                            <tr class="hover:bg-pink-50 transition-all duration-200 table-row-hover">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs px-2.5 py-1 rounded-lg bg-purple-100 text-purple-700 font-medium">
                                            {{ \Carbon\Carbon::parse($visit->visit_date)->translatedFormat('d M') }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($visit->visit_date)->translatedFormat('Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $visit->patient->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $visit->patient->species ?? '-' }} • {{ $visit->patient->breed ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $visit->patient->owner_name ?? '-' }}</p>
                                        @if($visit->patient->owner_phone)
                                            <p class="text-xs text-gray-500">{{ $visit->patient->owner_phone }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-900">{{ $visit->purpose ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @switch($visit->status)
                                        @case('completed')
                                            <span class="text-xs px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold inline-flex items-center gap-1">
                                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>Selesai
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="text-xs px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 font-semibold inline-flex items-center gap-1">
                                                <span class="w-2 h-2 bg-yellow-600 rounded-full"></span>Menunggu
                                            </span>
                                            @break
                                        @default
                                            <span class="text-xs px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold inline-flex items-center gap-1">
                                                <span class="w-2 h-2 bg-red-600 rounded-full"></span>{{ ucfirst($visit->status) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $visit->admin->name ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.visits.show', $visit->id) }}" 
                                           class="inline-flex items-center px-3 py-2 text-[#970747] font-semibold hover:text-[#7a0538] hover:bg-pink-50 rounded-lg transition-all shadow-sm hover:shadow-md-enhanced text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.visits.edit', $visit->id) }}" 
                                           class="inline-flex items-center px-3 py-2 text-amber-600 font-semibold hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-all shadow-sm hover:shadow-md-enhanced text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.visits.destroy', $visit->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kunjungan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-2 text-red-600 font-semibold hover:text-red-800 hover:bg-red-50 rounded-lg transition-all shadow-sm hover:shadow-md-enhanced text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16">
                                    <div class="flex flex-col items-center justify-center gap-4 text-gray-500">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 0v4m-9 8h10a2 2 0 012 2v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6a2 2 0 012-2z"></path>
                                        </svg>
                                        <div class="text-center">
                                            <p class="font-semibold text-base mb-1">Tidak ada data kunjungan</p>
                                            <p class="text-sm text-gray-400">Mulai dengan mencatat kunjungan baru</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($visits->count() > 0)
            <div class="mt-8">
                {{ $visits->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
@endsection