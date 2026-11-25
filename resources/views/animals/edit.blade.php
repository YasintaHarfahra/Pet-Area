{{-- 
=====================================================
VIEW: ANIMALS/EDIT.BLADE.PHP
=====================================================

Form untuk MENGEDIT DATA HEWAN.
Struktur mirip dengan create.blade.php tapi dengan data existing.
Pemilik tidak bisa diubah (harus hapus & buat baru jika salah pemilik).

Digunakan oleh: AnimalController@edit
=====================================================
--}}

@extends('layouts.admin_app')

@section('title', 'Edit Hewan - ' . $animal->nama_hewan)
@section('header_title', 'Edit Hewan')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Tombol Kembali --}}
    <a href="{{ route('animals.show', $animal->id) }}" 
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
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center text-3xl">
                    {{ $animal->species_emoji }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">✏️ Edit Data Hewan</h2>
                    <p class="text-sm text-slate-500">{{ $animal->animal_code }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('animals.update', $animal->id) }}" method="POST" class="p-6">
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
            {{-- SECTION: PEMILIK (READ-ONLY) --}}
            {{-- ================================ --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    Pemilik
                </h3>

                {{-- Info Pemilik (Tidak bisa diubah) --}}
                <div class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50">
                    <div class="w-12 h-12 rounded-lg bg-slate-300 flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($animal->owner->nama, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ $animal->owner->nama }}</p>
                        <p class="text-sm text-slate-500">📱 {{ $animal->owner->no_hp }}</p>
                    </div>
                    <span class="ml-auto text-xs text-slate-400 italic">Tidak dapat diubah</span>
                </div>
            </div>

            {{-- ================================ --}}
            {{-- SECTION: DATA HEWAN --}}
            {{-- ================================ --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    Data Hewan
                </h3>

                {{-- Nama Hewan --}}
                <div class="mb-4">
                    <label for="nama_hewan" class="block text-sm font-medium text-slate-700 mb-2">
                        Nama Hewan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_hewan" 
                           id="nama_hewan" 
                           value="{{ old('nama_hewan', $animal->nama_hewan) }}"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('nama_hewan') ? 'border-red-500' : 'border-slate-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all"
                           required>
                    @error('nama_hewan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis Hewan --}}
                <div class="mb-4">
                    <label for="jenis" class="block text-sm font-medium text-slate-700 mb-2">
                        Jenis Hewan <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis" 
                            id="jenis"
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('jenis') ? 'border-red-500' : 'border-slate-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all"
                            required>
                        @foreach($jenisOptions as $jenis)
                            <option value="{{ $jenis }}" {{ old('jenis', $animal->jenis) == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                        @endforeach
                    </select>
                    @error('jenis')
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

                {{-- Ras & Warna --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="ras" class="block text-sm font-medium text-slate-700 mb-2">Ras / Breed</label>
                        <input type="text" name="ras" id="ras" value="{{ old('ras', $animal->ras) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                    </div>
                    <div>
                        <label for="warna" class="block text-sm font-medium text-slate-700 mb-2">Warna</label>
                        <input type="text" name="warna" id="warna" value="{{ old('warna', $animal->warna) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                    </div>
                </div>

                {{-- Jenis Kelamin & Tanggal Lahir --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-slate-700 mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin"
                                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                            <option value="">-- Pilih --</option>
                            <option value="Jantan" {{ old('jenis_kelamin', $animal->jenis_kelamin) == 'Jantan' ? 'selected' : '' }}>♂️ Jantan</option>
                            <option value="Betina" {{ old('jenis_kelamin', $animal->jenis_kelamin) == 'Betina' ? 'selected' : '' }}>♀️ Betina</option>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-slate-700 mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                               value="{{ old('tanggal_lahir', $animal->tanggal_lahir?->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                    </div>
                </div>

                {{-- Berat & Umur --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="berat" class="block text-sm font-medium text-slate-700 mb-2">Berat (kg)</label>
                        <input type="number" name="berat" id="berat" value="{{ old('berat', $animal->berat) }}"
                               step="0.1"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                    </div>
                    <div>
                        <label for="umur" class="block text-sm font-medium text-slate-700 mb-2">Umur (teks)</label>
                        <input type="text" name="umur" id="umur" value="{{ old('umur', $animal->umur) }}"
                               placeholder="Contoh: 2 tahun 3 bulan"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                    </div>
                </div>

                {{-- Tanda Khusus --}}
                <div class="mb-4">
                    <label for="tanda_khusus" class="block text-sm font-medium text-slate-700 mb-2">Tanda Khusus</label>
                    <textarea name="tanda_khusus" id="tanda_khusus" rows="2"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">{{ old('tanda_khusus', $animal->tanda_khusus) }}</textarea>
                </div>

                {{-- Riwayat Alergi --}}
                <div class="mb-4">
                    <label for="riwayat_alergi" class="block text-sm font-medium text-slate-700 mb-2">
                        ⚠️ Riwayat Alergi <span class="text-red-500 text-xs">(PENTING)</span>
                    </label>
                    <textarea name="riwayat_alergi" id="riwayat_alergi" rows="2"
                              placeholder="Alergi obat, makanan, atau kondisi tertentu"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">{{ old('riwayat_alergi', $animal->riwayat_alergi) }}</textarea>
                </div>

                {{-- Catatan --}}
                <div class="mb-4">
                    <label for="catatan" class="block text-sm font-medium text-slate-700 mb-2">Catatan</label>
                    <textarea name="catatan" id="catatan" rows="2"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">{{ old('catatan', $animal->catatan) }}</textarea>
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-center gap-3">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1"
                           {{ old('is_active', $animal->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                    <label for="is_active" class="text-sm text-slate-700">
                        Hewan aktif (masih hidup / masih dipelihara)
                    </label>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('animals.show', $animal->id) }}" 
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

    {{-- Danger Zone --}}
    <div class="mt-6 bg-red-50 rounded-2xl border border-red-200 overflow-hidden">
        <div class="px-6 py-4">
            <h3 class="font-bold text-red-700 mb-2">⚠️ Zona Berbahaya</h3>
            <p class="text-sm text-red-600 mb-4">
                Menghapus hewan akan menghapus SEMUA rekam medis terkait. Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <form action="{{ route('animals.destroy', $animal->id) }}" 
                  method="POST" 
                  onsubmit="return confirm('PERHATIAN!\n\nAnda akan menghapus {{ $animal->nama_hewan }} beserta {{ $animal->medicalRecords->count() }} rekam medis.\n\nApakah Anda yakin?');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
                    🗑️ Hapus Hewan & Rekam Medis
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
