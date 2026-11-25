@extends('layouts.admin_app')

@section('title', 'Pendaftaran - Pet Area Clinic')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Pemilik & Hewan</h1>
            <p class="text-slate-500 text-sm">Kelola data pemilik dan hewan peliharaan</p>
        </div>
        <a href="{{ route('admin.registration.owners.create') }}" 
           class="mt-4 md:mt-0 px-6 py-3 bg-[#970747] hover:bg-[#7a0538] text-white rounded-lg inline-flex items-center gap-2 font-semibold shadow-sm transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pemilik Baru
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-300 text-emerald-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Search Box --}}
    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-5 mb-6">
        <form action="{{ route('admin.registration.owners.index') }}" method="GET" class="flex gap-4">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search }}" 
                       placeholder="Cari nama pemilik, hewan, jenis, atau ras..." 
                       class="w-full pl-12 pr-4 py-3 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#970747] focus:border-[#970747] text-slate-800 placeholder-slate-400 transition">
            </div>
            <button type="submit" class="px-6 py-3 bg-[#970747] text-white rounded-lg hover:bg-[#7a0538] font-semibold transition">
                Cari
            </button>
            @if($search)
            <a href="{{ route('admin.registration.owners.index', ['tab' => $tab]) }}" 
               class="px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-medium transition">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- TAB NAVIGATION --}}
    <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
        <div class="border-b border-slate-200">
            <nav class="flex" aria-label="Tabs">
                <a href="{{ route('admin.registration.owners.index', ['tab' => 'owners', 'search' => $search]) }}" 
                   class="flex-1 py-4 px-6 text-center font-semibold border-b-3 transition-all {{ $tab === 'owners' ? 'border-[#970747] text-[#7a0538] bg-pink-50' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Data Pemilik</span>
                        <span class="px-2.5 py-1 text-xs rounded-full font-bold {{ $tab === 'owners' ? 'bg-[#970747] text-white' : 'bg-slate-200 text-slate-600' }}">
                            {{ $totalOwners }}
                        </span>
                    </div>
                </a>
                <a href="{{ route('admin.registration.owners.index', ['tab' => 'pets', 'search' => $search]) }}" 
                   class="flex-1 py-4 px-6 text-center font-semibold border-b-3 transition-all {{ $tab === 'pets' ? 'border-emerald-600 text-emerald-700 bg-emerald-50' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span>Semua Pasien (Hewan)</span>
                        <span class="px-2.5 py-1 text-xs rounded-full font-bold {{ $tab === 'pets' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                            {{ $totalPets }}
                        </span>
                    </div>
                </a>
            </nav>
        </div>

        {{-- TAB CONTENT: OWNERS --}}
        @if($tab === 'owners')
        <div class="p-0">
            @if($owners->isEmpty())
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-700 mb-2">Belum ada data pemilik</h3>
                <p class="text-slate-500 mb-6">Mulai dengan menambahkan pemilik baru ke sistem</p>
                <a href="{{ route('admin.registration.owners.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-[#970747] text-white rounded-lg hover:bg-[#7a0538] font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pemilik Pertama
                </a>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Pemilik</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">Jumlah Hewan</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($owners as $owner)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-[#970747] flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">{{ strtoupper(substr($owner->nama, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $owner->nama }}</p>
                                        <p class="text-sm text-slate-500 font-mono">{{ $owner->owner_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-800">{{ $owner->no_hp }}</p>
                                <p class="text-sm text-slate-500">{{ $owner->email ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold {{ $owner->pets_count > 0 ? 'bg-pink-100 text-[#7a0538]' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $owner->pets_count }} Hewan
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.registration.owners.show', $owner) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-[#970747] text-white rounded-lg hover:bg-[#7a0538] text-sm font-semibold transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($owners->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $owners->links() }}
            </div>
            @endif
            @endif
        </div>
        @endif

        {{-- TAB CONTENT: PETS (FLAT LIST) --}}
        @if($tab === 'pets')
        <div class="p-0">
            @if($pets->isEmpty())
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-700 mb-2">Belum ada hewan terdaftar</h3>
                <p class="text-slate-500">Silakan tambahkan hewan melalui halaman pemilik</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">No. RM</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Hewan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Jenis & Ras</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Pemilik</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">Terakhir Periksa</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($pets as $pet)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-bold bg-[#970747]/10 text-[#970747]">
                                    {{ $pet->no_rm }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($pet->foto_hewan_url)
                                        <img src="{{ $pet->foto_hewan_url }}" alt="Foto {{ $pet->nama_hewan }}" class="w-11 h-11 rounded-lg object-cover border border-slate-200">
                                    @else
                                        <div class="w-11 h-11 rounded-lg bg-slate-200 flex items-center justify-center text-slate-600 font-bold">
                                            {{ strtoupper(substr($pet->nama_hewan, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                    <p class="font-semibold text-slate-800">{{ $pet->nama_hewan }}</p>
                                    <div class="flex gap-2 mt-1">
                                        <span class="px-2 py-0.5 text-xs rounded font-medium {{ ($pet->status_hidup ?? 'hidup') === 'mati' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">{{ ($pet->status_hidup ?? 'hidup') === 'mati' ? 'Meninggal' : 'Aktif' }}</span>
                                        <span class="px-2 py-0.5 text-xs bg-slate-200 text-slate-700 rounded font-medium">{{ $pet->jenis_kelamin ?? '-' }}</span>
                                        <span class="px-2 py-0.5 text-xs bg-slate-200 text-slate-700 rounded font-medium">{{ $pet->umur ?? '-' }}</span>
                                    </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-800">{{ $pet->jenis }}</p>
                                <p class="text-sm text-slate-500">{{ $pet->ras ?? 'Ras tidak dicatat' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.registration.owners.show', $pet->owner) }}" 
                                   class="text-[#970747] hover:text-[#7a0538] font-semibold hover:underline">
                                    {{ $pet->owner->nama ?? 'N/A' }}
                                </a>
                                <p class="text-sm text-slate-500">{{ $pet->owner->no_hp ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($pet->latestMedicalRecord && $pet->latestMedicalRecord->visit_date)
                                    <span class="text-sm text-slate-700 font-medium">{{ $pet->latestMedicalRecord->visit_date->format('d M Y') }}</span>
                                @else
                                    <span class="text-sm text-slate-400 italic">Belum pernah</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.registration.owners.show', $pet->owner) }}" 
                                       class="p-2 bg-pink-50 text-[#970747] hover:bg-pink-100 rounded-lg transition" title="Lihat Pemilik">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.registration.pets.edit', $pet) }}" 
                                       class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition" title="Edit Hewan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($pets->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $pets->links() }}
            </div>
            @endif
            @endif
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){let t;document.querySelectorAll('form[method="GET"] input[type="text"]').forEach(function(i){i.addEventListener('input',function(){clearTimeout(t);t=setTimeout(function(){i.closest('form').submit()},400)})});document.querySelectorAll('form[method="GET"] select,form[method="GET"] input[type="date"]').forEach(function(el){el.addEventListener('change',function(){el.closest('form').submit()})})});
</script>
@endsection
