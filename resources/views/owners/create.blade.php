{{-- 
=====================================================
VIEW: OWNERS/CREATE.BLADE.PHP
=====================================================

Form untuk MENDAFTARKAN PEMILIK BARU.
Pemilik adalah entitas utama dalam sistem.
Setelah pemilik terdaftar, baru bisa menambah hewan.

Digunakan oleh: OwnerController@create
=====================================================
--}}

@extends('layouts.admin_app')

@section('title', 'Daftarkan Pemilik Baru')
@section('header_title', 'Daftarkan Pemilik Baru')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Tombol Kembali --}}
    <a href="{{ route('owners.index') }}" 
       class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 mb-6 group">
        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar
    </a>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-teal-50">
            <h2 class="text-lg font-bold text-slate-800">📝 Formulir Pendaftaran Pemilik</h2>
            <p class="text-sm text-slate-500 mt-1">Isi data pemilik hewan peliharaan dengan lengkap</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('owners.store') }}" method="POST" class="p-6">
            @csrf

            {{-- Error Display --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <p class="font-semibold mb-2">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ================================ --}}
            {{-- SECTION: DATA WAJIB --}}
            {{-- ================================ --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    Data Wajib
                </h3>

                {{-- Nama Lengkap --}}
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-slate-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama" 
                           id="nama" 
                           value="{{ old('nama') }}"
                           placeholder="Masukkan nama lengkap pemilik"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('nama') ? 'border-red-500' : 'border-slate-300' }} focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all"
                           required>
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No. Handphone --}}
                <div class="mb-4">
                    <label for="no_hp" class="block text-sm font-medium text-slate-700 mb-2">
                        No. Handphone <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="no_hp" 
                           id="no_hp" 
                           value="{{ old('no_hp') }}"
                           placeholder="Contoh: 08XXXXXXXXXX"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('no_hp') ? 'border-red-500' : 'border-slate-300' }} focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all font-mono"
                           required>
                    @error('no_hp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-400 mt-1">Nomor ini digunakan untuk pencarian dan notifikasi</p>
                </div>
            </div>

            {{-- ================================ --}}
            {{-- SECTION: DATA TAMBAHAN --}}
            {{-- ================================ --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                    Data Tambahan (Opsional)
                </h3>

                {{-- Alamat --}}
                <div class="mb-4">
                    <label for="alamat" class="block text-sm font-medium text-slate-700 mb-2">
                        Alamat
                    </label>
                    <textarea name="alamat" 
                              id="alamat" 
                              rows="3"
                              placeholder="Alamat lengkap pemilik"
                              class="w-full px-4 py-3 rounded-xl border {{ $errors->has('alamat') ? 'border-red-500' : 'border-slate-300' }} focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email & No KTP (2 Kolom) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                            Email
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}"
                               placeholder="pemilik@example.test"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }} focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_ktp" class="block text-sm font-medium text-slate-700 mb-2">
                            No. KTP
                        </label>
                        <input type="text" 
                               name="no_ktp" 
                               id="no_ktp" 
                               value="{{ old('no_ktp') }}"
                               placeholder="16 digit nomor KTP"
                               maxlength="16"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('no_ktp') ? 'border-red-500' : 'border-slate-300' }} focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all font-mono">
                        @error('no_ktp')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="mb-4">
                    <label for="catatan" class="block text-sm font-medium text-slate-700 mb-2">
                        Catatan Internal
                    </label>
                    <textarea name="catatan" 
                              id="catatan" 
                              rows="2"
                              placeholder="Catatan khusus tentang pemilik ini (hanya terlihat oleh staff)"
                              class="w-full px-4 py-3 rounded-xl border {{ $errors->has('catatan') ? 'border-red-500' : 'border-slate-300' }} focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('owners.index') }}" 
                   class="px-6 py-3 text-slate-600 hover:text-slate-800 font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl font-semibold shadow-lg shadow-emerald-200 transition-all">
                    💾 Simpan Pemilik
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
