@extends('layouts.admin_app')

@section('title', 'Edit Pemilik - Pet Area Clinic')

@push('styles')
<style>
    .fade-up {
        animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .input-focus {
        transition: all 0.2s ease;
    }
    .input-focus:focus {
        box-shadow: 0 0 0 4px rgba(151, 7, 71, 0.1);
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    
    {{-- Breadcrumb --}}
    <nav class="mb-8 fade-up">
        <a href="{{ route('admin.registration.owners.show', $owner) }}" 
           class="inline-flex items-center gap-2 text-gray-500 hover:text-[#970747] transition-colors group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span class="text-sm font-medium">Kembali ke {{ $owner->nama }}</span>
        </a>
    </nav>

    {{-- Error Messages --}}
    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3 fade-up">
        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <ul class="text-red-700 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- LEFT: Owner Info Card --}}
        <div class="lg:col-span-4 fade-up" style="animation-delay: 0.05s">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 mx-auto bg-[#970747] rounded-full flex items-center justify-center mb-4 shadow-lg shadow-pink-200/50">
                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr($owner->nama, 0, 1)) }}</span>
                    </div>
                    @if($owner->owner_code)
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#970747]/10 rounded-lg mb-2">
                        <span class="text-sm font-bold text-[#970747]">{{ $owner->owner_code }}</span>
                    </div>
                    @endif
                    <h3 class="font-bold text-gray-900">{{ $owner->nama }}</h3>
                    <p class="text-sm text-gray-500">{{ $owner->pets->count() }} hewan terdaftar</p>
                </div>
                
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-gray-700">{{ $owner->no_hp }}</span>
                    </div>
                    @if($owner->email)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-700 truncate">{{ $owner->email }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Kode pemilik tidak dapat diubah</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Form --}}
        <div class="lg:col-span-8 fade-up" style="animation-delay: 0.1s">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-100">
                    <h1 class="text-xl font-bold text-gray-900">Edit Data Pemilik</h1>
                    <p class="text-sm text-gray-500 mt-1">Perbarui informasi pemilik hewan</p>
                </div>

                {{-- Form --}}
                <form action="{{ route('admin.registration.owners.update', $owner) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-6">
                        
                        {{-- Row 1: Nama & No HP --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" value="{{ old('nama', $owner->nama) }}" required
                                       class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none"
                                       placeholder="Masukkan nama lengkap">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    No. HP / WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="no_hp" value="{{ old('no_hp', $owner->no_hp) }}" required
                                       class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none"
                                       placeholder="08XXXXXXXXXX">
                            </div>
                        </div>

                        {{-- Row 2: Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $owner->email) }}"
                                   class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none"
                                   placeholder="nama@example.test (opsional)">
                        </div>

                        {{-- Row 3: Alamat --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <textarea name="alamat" rows="3"
                                      class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none resize-none"
                                      placeholder="Alamat lengkap (opsional)">{{ old('alamat', $owner->alamat) }}</textarea>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.registration.owners.show', $owner) }}" 
                           class="px-5 py-2.5 text-gray-600 hover:text-gray-800 font-medium text-sm transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-[#970747] hover:bg-[#7a0538] text-white rounded-xl font-semibold text-sm transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
