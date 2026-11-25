@extends('layouts.admin_app')

@section('title', 'Tambah Pemilik Baru - Pet Area Clinic')

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
        <a href="{{ route('admin.registration.owners.index') }}" 
           class="inline-flex items-center gap-2 text-gray-500 hover:text-[#970747] transition-colors group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span class="text-sm font-medium">Kembali ke Daftar Pemilik</span>
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
        
        {{-- LEFT: Info Card --}}
        <div class="lg:col-span-4 fade-up" style="animation-delay: 0.05s">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 mx-auto bg-[#970747] rounded-full flex items-center justify-center mb-4 shadow-lg shadow-pink-200/50">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Pemilik Baru</h3>
                    <p class="text-sm text-gray-500">Daftarkan data pemilik hewan</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-3 bg-pink-50 rounded-xl">
                        <div class="w-8 h-8 bg-[#970747]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-[#970747]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Tips</p>
                            <p class="text-xs text-gray-500">Gunakan nomor WhatsApp aktif agar mudah dihubungi</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Langkah Selanjutnya</p>
                            <p class="text-xs text-gray-500">Setelah simpan, tambahkan data hewan peliharaan</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Data tersimpan dengan aman</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Form --}}
        <div class="lg:col-span-8 fade-up" style="animation-delay: 0.1s">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-100">
                    <h1 class="text-xl font-bold text-gray-900">Tambah Pemilik Baru</h1>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi informasi pemilik hewan</p>
                </div>

                {{-- Form --}}
                <form action="{{ route('admin.registration.owners.store') }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-6">
                        
                        {{-- Row 1: Nama & No HP --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" value="{{ old('nama') }}" required
                                       class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none"
                                       placeholder="Masukkan nama lengkap">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    No. HP / WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="no_hp" value="{{ old('no_hp') }}" required
                                       class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none"
                                       placeholder="08XXXXXXXXXX">
                                <p class="text-xs text-gray-400 mt-1">Format otomatis ke +62</p>
                            </div>
                        </div>

                        {{-- Row 2: Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none"
                                   placeholder="nama@example.test (opsional)">
                        </div>

                        {{-- Row 3: Alamat --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <textarea name="alamat" rows="3"
                                      class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none resize-none"
                                      placeholder="Alamat lengkap (opsional)">{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.registration.owners.index') }}" 
                           class="px-5 py-2.5 text-gray-600 hover:text-gray-800 font-medium text-sm transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-[#970747] hover:bg-[#7a0538] text-white rounded-xl font-semibold text-sm transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Pemilik
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
