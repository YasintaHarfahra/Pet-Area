{{-- 
=====================================================
VIEW: OWNERS/INDEX.BLADE.PHP
=====================================================

Halaman ini menampilkan DAFTAR PEMILIK dengan fitur:
- Pencarian berdasarkan nama atau no HP
- Tabel pemilik dengan jumlah hewan
- Tombol aksi: Lihat, Edit, Hapus

Digunakan oleh: OwnerController@index
=====================================================
--}}

@extends('layouts.admin_app')

@section('title', 'Daftar Pemilik - Pet Area')
@section('header_title', 'Manajemen Pemilik')

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- Header dengan Search --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Daftar Pemilik Hewan</h1>
            <p class="text-slate-500 mt-1">Kelola data pemilik dan hewan peliharaan</p>
        </div>
        
        {{-- Tombol Tambah Pemilik --}}
        <a href="{{ route('owners.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pemilik
        </a>
    </div>

    {{-- Card Pencarian --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
        <form action="{{ route('owners.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            {{-- Input Pencarian --}}
            <div class="flex-1 relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama pemilik atau nomor HP..."
                       class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            
            {{-- Tombol Cari --}}
            <button type="submit" 
                    class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg font-medium transition-colors">
                Cari
            </button>
            
            {{-- Tombol Reset (jika ada search) --}}
            @if(request('search'))
                <a href="{{ route('owners.index') }}" 
                   class="px-4 py-2.5 border border-slate-300 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Pemilik --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left py-3 px-4 font-semibold text-slate-700">Kode</th>
                    <th class="text-left py-3 px-4 font-semibold text-slate-700">Nama Pemilik</th>
                    <th class="text-left py-3 px-4 font-semibold text-slate-700">No. HP</th>
                    <th class="text-left py-3 px-4 font-semibold text-slate-700 hidden md:table-cell">Alamat</th>
                    <th class="text-center py-3 px-4 font-semibold text-slate-700">Hewan</th>
                    <th class="text-center py-3 px-4 font-semibold text-slate-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($owners as $owner)
                    <tr class="hover:bg-slate-50 transition-colors">
                        {{-- Kode Pemilik --}}
                        <td class="py-3 px-4">
                            <span class="font-mono text-sm text-slate-500">{{ $owner->owner_code }}</span>
                        </td>
                        
                        {{-- Nama dengan Avatar --}}
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($owner->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $owner->nama }}</p>
                                    @if($owner->email)
                                        <p class="text-sm text-slate-400">{{ $owner->email }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        {{-- No HP --}}
                        <td class="py-3 px-4">
                            <span class="font-mono text-slate-600">{{ $owner->no_hp }}</span>
                        </td>
                        
                        {{-- Alamat --}}
                        <td class="py-3 px-4 hidden md:table-cell">
                            <span class="text-slate-600 text-sm line-clamp-1">{{ $owner->alamat ?? '-' }}</span>
                        </td>
                        
                        {{-- Jumlah Hewan --}}
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                {{ $owner->animals_count > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }} 
                                font-bold text-sm">
                                {{ $owner->animals_count }}
                            </span>
                        </td>
                        
                        {{-- Aksi --}}
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center gap-1">
                                {{-- Lihat Detail --}}
                                <a href="{{ route('owners.show', $owner->id) }}" 
                                   class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                   title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                
                                {{-- Edit --}}
                                <a href="{{ route('owners.edit', $owner->id) }}" 
                                   class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                
                                {{-- Hapus --}}
                                <form action="{{ route('owners.destroy', $owner->id) }}" method="POST" 
                                      onsubmit="return confirm('Hapus pemilik ini? Semua hewan dan rekam medis akan ikut terhapus!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="text-slate-400">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                @if(request('search'))
                                    <p>Tidak ditemukan pemilik dengan kata kunci "{{ request('search') }}"</p>
                                @else
                                    <p>Belum ada data pemilik</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- Pagination --}}
        @if($owners->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                {{ $owners->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){let t;document.querySelectorAll('form[method="GET"] input[type="text"]').forEach(function(i){i.addEventListener('input',function(){clearTimeout(t);t=setTimeout(function(){i.closest('form').submit()},400)})});document.querySelectorAll('form[method="GET"] select,form[method="GET"] input[type="date"]').forEach(function(el){el.addEventListener('change',function(){el.closest('form').submit()})})});
</script>
@endsection
