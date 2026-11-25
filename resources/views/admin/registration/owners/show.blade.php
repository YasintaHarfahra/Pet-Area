@extends('layouts.admin_app')

@section('title', 'Detail Pemilik - Pet Area Clinic')

@push('styles')
<style>
    /* ===== 2026 MINIMALIST DESIGN SYSTEM ===== */
    
    /* Smooth Page Load */
    .fade-up {
        animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(24px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Stagger children */
    .stagger > *:nth-child(1) { animation-delay: 0.05s; }
    .stagger > *:nth-child(2) { animation-delay: 0.1s; }
    .stagger > *:nth-child(3) { animation-delay: 0.15s; }
    .stagger > *:nth-child(4) { animation-delay: 0.2s; }
    .stagger > *:nth-child(5) { animation-delay: 0.25s; }
    .stagger > *:nth-child(6) { animation-delay: 0.3s; }
    
    /* Elegant Card Hover */
    .card-hover {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -16px rgba(151, 7, 71, 0.15);
    }
    
    /* Subtle Button Press */
    .btn-press {
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .btn-press:active {
        transform: scale(0.97);
    }
    
    /* Smooth Link Underline */
    .link-underline {
        position: relative;
    }
    
    .link-underline::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #970747;
        transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .link-underline:hover::after {
        width: 100%;
    }
    
    /* Avatar Ring Animation */
    .avatar-ring {
        position: relative;
    }
    
    .avatar-ring::before {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        border: 2px solid rgba(151, 7, 71, 0.2);
        animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse-ring {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.05); }
    }
    
    /* Modal Backdrop Blur */
    .modal-backdrop {
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    
    /* Floating Label Input */
    .input-float {
        transition: all 0.3s ease;
    }
    
    .input-float:focus {
        box-shadow: 0 0 0 4px rgba(151, 7, 71, 0.1);
    }
    
    /* Pet Card Subtle Glow */
    .pet-glow {
        position: relative;
    }
    
    .pet-glow::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 1rem;
        padding: 1px;
        background: linear-gradient(135deg, transparent 40%, rgba(151, 7, 71, 0.1) 100%);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .pet-glow:hover::before {
        opacity: 1;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    
    {{-- Breadcrumb --}}
    <nav class="mb-8 fade-up">
        <a href="{{ route('admin.registration.owners.index') }}" 
           class="inline-flex items-center gap-2 text-gray-500 hover:text-[#970747] transition-colors group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span class="text-sm font-medium link-underline">Kembali ke Daftar</span>
        </a>
    </nav>

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 fade-up">
        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p class="text-emerald-800 text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- ===== LEFT: Owner Profile ===== --}}
        <div class="lg:col-span-4 fade-up" style="animation-delay: 0.1s">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden sticky top-6">
                
                {{-- Profile Header --}}
                <div class="relative px-6 pt-10 pb-8 text-center bg-gradient-to-b from-pink-50/80 to-white">
                    {{-- Avatar --}}
                    <div class="avatar-ring w-24 h-24 mx-auto mb-5">
                        <div class="w-full h-full rounded-full bg-[#970747] flex items-center justify-center shadow-lg shadow-pink-200/50">
                            <span class="text-3xl font-bold text-white">{{ strtoupper(substr($owner->nama, 0, 1)) }}</span>
                        </div>
                    </div>
                    
                    {{-- Name & ID --}}
                    <h1 class="text-xl font-bold text-gray-900 mb-1">{{ $owner->nama }}</h1>
                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 font-medium">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                        {{ $owner->owner_code ?? 'ID #'.$owner->id_owner }}
                    </span>
                </div>

                {{-- Contact Info --}}
                <div class="px-6 py-5 space-y-4 border-t border-gray-100">
                    {{-- Phone --}}
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Telepon</p>
                            <p class="text-gray-900 font-semibold truncate">{{ $owner->no_hp }}</p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Email</p>
                            <p class="text-gray-900 font-semibold truncate">{{ $owner->email ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Alamat</p>
                            <p class="text-gray-900 font-semibold">{{ $owner->alamat ?? 'Belum diisi' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="px-6 pb-6 pt-2 space-y-3">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $owner->no_hp) }}" 
                       target="_blank"
                       class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl flex items-center justify-center gap-2 font-semibold text-sm transition-colors btn-press">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884"/>
                        </svg>
                        WhatsApp
                    </a>
                    <a href="{{ route('admin.registration.owners.edit', $owner) }}" 
                       class="w-full py-3 bg-white border border-gray-200 hover:border-[#970747] hover:text-[#970747] text-gray-700 rounded-xl flex items-center justify-center gap-2 font-semibold text-sm transition-all btn-press">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Profil
                    </a>

                    <form action="{{ route('admin.registration.owners.destroy', $owner) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pemilik ini? Semua data hewan milik pemilik ini juga akan dihapus!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl flex items-center justify-center gap-2 font-semibold text-sm transition-all btn-press">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Hapus Pemilik
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===== RIGHT: Pets List ===== --}}
        <div class="lg:col-span-8 fade-up" style="animation-delay: 0.2s">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Hewan Peliharaan</h2>
                        <p class="text-sm text-gray-500">{{ $owner->pets->count() }} hewan terdaftar</p>
                    </div>
                    <a href="{{ route('admin.registration.owners.pets.create', $owner) }}" 
                       class="px-4 py-2.5 bg-[#970747] hover:bg-[#7a0538] text-white rounded-xl flex items-center gap-2 font-semibold text-sm transition-colors btn-press">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah
                    </a>
                </div>

                {{-- Pet List --}}
                <div class="p-6">
                    @if($owner->pets->isEmpty())
                    {{-- Empty State --}}
                    <div class="text-center py-16">
                        <div class="w-20 h-20 mx-auto bg-gray-50 rounded-2xl flex items-center justify-center mb-5">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-gray-900 font-semibold mb-1">Belum ada hewan</h3>
                        <p class="text-gray-500 text-sm mb-6">Tambahkan hewan peliharaan pertama</p>
                        <a href="{{ route('admin.registration.owners.pets.create', $owner) }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#970747] hover:bg-[#7a0538] text-white rounded-xl font-semibold text-sm transition-colors btn-press">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Hewan
                        </a>
                    </div>
                    @else
                    {{-- Pet Cards Grid --}}
                    <div class="space-y-4 stagger">
                        @foreach($owner->pets as $pet)
                        @php
                            $umurText = '-';
                            if ($pet->tgl_lahir) {
                                $lahir = \Carbon\Carbon::parse($pet->tgl_lahir);
                                $now = \Carbon\Carbon::now();
                                $years = (int) $lahir->diffInYears($now);
                                $months = (int) $lahir->copy()->addYears($years)->diffInMonths($now);
                                
                                if ($years > 0) {
                                    $umurText = $months > 0 ? "{$years} thn {$months} bln" : "{$years} tahun";
                                } else {
                                    $umurText = "{$months} bulan";
                                }
                            }
                            
                            $avatarColors = [
                                'Anjing' => 'bg-amber-500',
                                'Kucing' => 'bg-violet-500',
                                'Burung' => 'bg-sky-500',
                                'Kelinci' => 'bg-pink-500',
                                'Hamster' => 'bg-emerald-500',
                            ];
                            $avatarColor = $avatarColors[$pet->jenis] ?? 'bg-gray-500';
                        @endphp
                        <div class="pet-glow card-hover bg-white rounded-2xl border border-gray-100 p-5 fade-up">
                            <div class="flex items-center gap-5">
                                {{-- Avatar --}}
                                @if($pet->foto_hewan_url)
                                <img src="{{ $pet->foto_hewan_url }}" alt="Foto {{ $pet->nama_hewan }}"
                                     class="w-14 h-14 rounded-xl object-cover flex-shrink-0 shadow-sm border border-gray-100">
                                @else
                                <div class="w-14 h-14 {{ $avatarColor }} rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <span class="text-xl font-bold text-white">{{ strtoupper(substr($pet->nama_hewan, 0, 1)) }}</span>
                                </div>
                                @endif
                                
                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded bg-[#970747]/10 text-[#970747]">{{ $pet->no_rm }}</span>
                                        <h3 class="font-bold text-gray-900 truncate">{{ $pet->nama_hewan }}</h3>
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ ($pet->status_hidup ?? 'hidup') === 'mati' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ ($pet->status_hidup ?? 'hidup') === 'mati' ? 'Meninggal' : 'Aktif' }}
                                        </span>
                                        @if($pet->jenis_kelamin)
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $pet->jenis_kelamin == 'jantan' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }}">
                                            {{ ucfirst($pet->jenis_kelamin) }}
                                        </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        {{ $pet->jenis }}{{ $pet->ras ? ' · ' . $pet->ras : '' }} · {{ $umurText }}
                                    </p>
                                    @if(($pet->status_hidup ?? 'hidup') === 'mati')
                                    <p class="text-xs text-red-600 mt-1">
                                        {{ $pet->tanggal_kematian ? 'Meninggal pada ' . $pet->tanggal_kematian->format('d/m/Y') : 'Status meninggal' }}
                                    </p>
                                    @endif
                                </div>
                                
                                {{-- Actions --}}
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    @if(($pet->status_hidup ?? 'hidup') !== 'mati')
                                    <button type="button" 
                                            onclick="openQueueModal({{ $pet->id_pet }}, '{{ addslashes($pet->nama_hewan) }}')"
                                            class="w-9 h-9 bg-[#970747] hover:bg-[#7a0538] text-white rounded-lg flex items-center justify-center transition-colors btn-press"
                                            title="Daftar Antrian">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                    @else
                                    <button type="button"
                                            class="w-9 h-9 bg-gray-100 text-gray-400 rounded-lg flex items-center justify-center cursor-not-allowed"
                                            title="Hewan meninggal tidak dapat didaftarkan ke antrian"
                                            disabled>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                    @endif
                                    <a href="{{ route('admin.registration.pets.edit', $pet) }}" 
                                       class="w-9 h-9 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg flex items-center justify-center transition-colors btn-press"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.registration.pets.destroy', $pet) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-9 h-9 bg-red-50 hover:bg-red-100 text-red-500 rounded-lg flex items-center justify-center transition-colors btn-press"
                                                title="Hapus"
                                                data-name="{{ $pet->nama_hewan }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== QUEUE MODAL ===== --}}
<div id="queueModal" class="fixed inset-0 z-50 hidden" aria-modal="true">
    {{-- Backdrop --}}
    <div onclick="closeQueueModal()" class="modal-backdrop absolute inset-0 bg-gray-900/50 transition-opacity duration-300 opacity-0" id="modalBackdrop"></div>
    
    {{-- Modal --}}
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div id="modalContent" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0">
            <form id="queueForm" action="{{ route('admin.queues.store') }}" method="POST">
                @csrf
                <input type="hidden" name="pet_id" id="modal_pet_id">
                <input type="hidden" name="queue_date" value="{{ date('Y-m-d') }}">
                
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900">Daftar Antrian</h3>
                        <p class="text-sm text-gray-500" id="modal_pet_name">Nama Hewan</p>
                    </div>
                    <button type="button" onclick="closeQueueModal()" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label for="complaint" class="block text-sm font-medium text-gray-700 mb-2">Keluhan</label>
                        <textarea name="complaint" id="complaint" rows="3" 
                                  placeholder="Contoh: Muntah sejak kemarin..."
                                  class="input-float w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none transition-all resize-none text-sm"></textarea>
                    </div>
                    
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Kunjungan</p>
                            <p class="text-sm font-semibold text-gray-900">{{ now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 rounded-b-2xl">
                    <button type="button" onclick="closeQueueModal()" 
                            class="px-4 py-2.5 text-gray-600 hover:text-gray-800 font-medium text-sm transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-[#970747] hover:bg-[#7a0538] text-white rounded-xl font-semibold text-sm transition-colors btn-press">
                        Daftarkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Modal Functions
    function openQueueModal(petId, petName) {
        const modal = document.getElementById('queueModal');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('modalContent');
        
        document.getElementById('modal_pet_id').value = petId;
        document.getElementById('modal_pet_name').textContent = petName;
        document.getElementById('complaint').value = '';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Animate in
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeQueueModal() {
        const modal = document.getElementById('queueModal');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('modalContent');
        
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    // Close with Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeQueueModal();
    });

    // Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const petName = this.querySelector('button[data-name]').dataset.name;
            
            Swal.fire({
                title: 'Hapus hewan?',
                text: `"${petName}" akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl',
                    cancelButton: 'rounded-xl'
                }
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });
    });
</script>
@endpush
