{{-- 
=====================================================
VIEW: OWNERS/EDIT.BLADE.PHP
=====================================================

Form untuk MENGEDIT DATA PEMILIK.
Struktur mirip dengan create.blade.php tapi dengan data existing.

Digunakan oleh: OwnerController@edit
=====================================================
--}}

@extends('layouts.admin_app')

@section('title', 'Edit Pemilik - ' . $owner->nama)
@section('header_title', 'Edit Pemilik')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Tombol Kembali --}}
    <a href="{{ route('owners.show', $owner->id) }}" 
       class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 mb-6 group">
        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Detail
    </a>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-amber-50 to-orange-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($owner->nama, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">✏️ Edit Data Pemilik</h2>
                    <p class="text-sm text-slate-500">Kode: {{ $owner->owner_code }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('owners.update', $owner->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

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
                           value="{{ old('nama', $owner->nama) }}"
                           placeholder="Masukkan nama lengkap pemilik"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('nama') ? 'border-red-500' : 'border-slate-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all"
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
                           value="{{ old('no_hp', $owner->no_hp) }}"
                           placeholder="Contoh: 08XXXXXXXXXX"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('no_hp') ? 'border-red-500' : 'border-slate-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all font-mono"
                           required>
                    @error('no_hp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ================================ --}}
            {{-- SECTION: DATA TAMBAHAN --}}
            {{-- ================================ --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                    Data Tambahan
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
                              class="w-full px-4 py-3 rounded-xl border {{ $errors->has('alamat') ? 'border-red-500' : 'border-slate-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">{{ old('alamat', $owner->alamat) }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email & No KTP --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                            Email
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $owner->email) }}"
                               placeholder="pemilik@example.test"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
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
                               value="{{ old('no_ktp', $owner->no_ktp) }}"
                               placeholder="16 digit nomor KTP"
                               maxlength="16"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('no_ktp') ? 'border-red-500' : 'border-slate-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all font-mono">
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
                              placeholder="Catatan khusus tentang pemilik ini"
                              class="w-full px-4 py-3 rounded-xl border {{ $errors->has('catatan') ? 'border-red-500' : 'border-slate-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">{{ old('catatan', $owner->catatan) }}</textarea>
                    @error('catatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('owners.show', $owner->id) }}" 
                   class="px-6 py-3 text-slate-600 hover:text-slate-800 font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl font-semibold shadow-lg shadow-amber-200 transition-all">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Danger Zone - Hapus Pemilik --}}
    <div class="mt-6 bg-red-50 rounded-2xl border border-red-200 overflow-hidden">
        <div class="px-6 py-4">
            <h3 class="font-bold text-red-700 mb-2">⚠️ Zona Berbahaya</h3>
            <p class="text-sm text-red-600 mb-4">
                Menghapus pemilik akan menghapus SEMUA data hewan dan rekam medis terkait. Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <form action="{{ route('owners.destroy', $owner->id) }}" 
                  method="POST" 
                  onsubmit="return confirm('PERHATIAN!\n\nAnda akan menghapus pemilik {{ $owner->nama }} beserta {{ $owner->animals->count() }} hewan dan semua rekam medisnya.\n\nApakah Anda yakin?');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
                    🗑️ Hapus Pemilik & Semua Data
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
