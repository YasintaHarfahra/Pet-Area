{{-- 
=====================================================
VIEW: ANIMALS/CREATE.BLADE.PHP
=====================================================

Form untuk MENDAFTARKAN HEWAN BARU.
Hewan harus dikaitkan dengan pemilik yang sudah ada.
Jika akses dari halaman detail pemilik, owner_id sudah di-prefill.

Digunakan oleh: AnimalController@create
=====================================================
--}}

@extends('layouts.admin_app')

@section('title', 'Daftarkan Hewan Baru')
@section('header_title', 'Daftarkan Hewan Baru')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Tombol Kembali --}}
    @if(isset($selectedOwner))
        <a href="{{ route('owners.show', $selectedOwner->id) }}" 
           class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 mb-6 group">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke {{ $selectedOwner->nama }}
        </a>
    @else
        <a href="{{ route('animals.index') }}" 
           class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 mb-6 group">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar
        </a>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-pink-50 to-purple-50">
            <h2 class="text-lg font-bold text-slate-800">🐾 Formulir Pendaftaran Hewan</h2>
            <p class="text-sm text-slate-500 mt-1">Isi data hewan peliharaan dengan lengkap</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('animals.store') }}" method="POST" class="p-6">
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
            {{-- SECTION: PEMILIK --}}
            {{-- ================================ --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    Pemilik
                </h3>

                {{-- Pilih Pemilik --}}
                <div class="mb-4">
                    <label for="owner_id" class="block text-sm font-medium text-slate-700 mb-2">
                        Pilih Pemilik <span class="text-red-500">*</span>
                    </label>
                    
                    @if(isset($selectedOwner))
                        {{-- Jika sudah di-prefill --}}
                        <input type="hidden" name="owner_id" value="{{ $selectedOwner->id }}">
                        <div class="flex items-center gap-3 p-4 rounded-xl border-2 border-emerald-200 bg-emerald-50">
                            <div class="w-12 h-12 rounded-lg bg-emerald-500 flex items-center justify-center text-white text-xl font-bold">
                                {{ strtoupper(substr($selectedOwner->nama, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">{{ $selectedOwner->nama }}</p>
                                <p class="text-sm text-slate-500">📱 {{ $selectedOwner->no_hp }}</p>
                            </div>
                            <a href="{{ route('animals.create') }}" 
                               class="ml-auto text-sm text-emerald-600 hover:underline">
                                Ganti
                            </a>
                        </div>
                    @else
                        {{-- Pilihan Dropdown --}}
                        <select name="owner_id" 
                                id="owner_id"
                                class="w-full px-4 py-3 rounded-xl border {{ $errors->has('owner_id') ? 'border-red-500' : 'border-slate-300' }} focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all"
                                required>
                            <option value="">-- Pilih Pemilik --</option>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->nama }} ({{ $owner->no_hp }})
                                </option>
                            @endforeach
                        </select>
                        @error('owner_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <p class="text-xs text-slate-400 mt-2">
                            Pemilik belum terdaftar? 
                            <a href="{{ route('owners.create') }}" class="text-[#970747] hover:underline">Daftarkan pemilik baru</a>
                        </p>
                    @endif
                </div>
            </div>

            {{-- ================================ --}}
            {{-- SECTION: DATA HEWAN --}}
            {{-- ================================ --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    Data Hewan (Wajib)
                </h3>

                {{-- Nama Hewan --}}
                <div class="mb-4">
                    <label for="nama_hewan" class="block text-sm font-medium text-slate-700 mb-2">
                        Nama Hewan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_hewan" 
                           id="nama_hewan" 
                           value="{{ old('nama_hewan') }}"
                           placeholder="Contoh: Milo, Bella, Ciko"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('nama_hewan') ? 'border-red-500' : 'border-slate-300' }} focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all"
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
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('jenis') ? 'border-red-500' : 'border-slate-300' }} focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all"
                            required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($jenisOptions as $jenis)
                            <option value="{{ $jenis }}" {{ old('jenis') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
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
                    Data Tambahan (Opsional)
                </h3>

                {{-- Ras & Warna --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="ras" class="block text-sm font-medium text-slate-700 mb-2">
                            Ras / Breed
                        </label>
                        <input type="text" 
                               name="ras" 
                               id="ras" 
                               value="{{ old('ras') }}"
                               placeholder="Contoh: Golden Retriever, Persian"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                    </div>

                    <div>
                        <label for="warna" class="block text-sm font-medium text-slate-700 mb-2">
                            Warna
                        </label>
                        <input type="text" 
                               name="warna" 
                               id="warna" 
                               value="{{ old('warna') }}"
                               placeholder="Contoh: Coklat, Putih Hitam"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                    </div>
                </div>

                {{-- Jenis Kelamin & Tanggal Lahir --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-slate-700 mb-2">
                            Jenis Kelamin
                        </label>
                        <select name="jenis_kelamin" 
                                id="jenis_kelamin"
                                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                            <option value="">-- Pilih --</option>
                            <option value="Jantan" {{ old('jenis_kelamin') == 'Jantan' ? 'selected' : '' }}>♂️ Jantan</option>
                            <option value="Betina" {{ old('jenis_kelamin') == 'Betina' ? 'selected' : '' }}>♀️ Betina</option>
                        </select>
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-slate-700 mb-2">
                            Tanggal Lahir
                        </label>
                        <input type="date" 
                               name="tanggal_lahir" 
                               id="tanggal_lahir" 
                               value="{{ old('tanggal_lahir') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                    </div>
                </div>

                {{-- Berat & Umur --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="berat" class="block text-sm font-medium text-slate-700 mb-2">
                            Berat (kg)
                        </label>
                        <input type="number" 
                               name="berat" 
                               id="berat" 
                               value="{{ old('berat') }}"
                               step="0.1"
                               placeholder="Contoh: 5.5"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                    </div>

                    <div>
                        <label for="umur" class="block text-sm font-medium text-slate-700 mb-2">
                            Umur (teks)
                        </label>
                        <input type="text" 
                               name="umur" 
                               id="umur" 
                               value="{{ old('umur') }}"
                               placeholder="Contoh: 2 tahun 3 bulan"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                        <p class="text-xs text-slate-400 mt-1">Isi jika tidak tahu tanggal lahir pasti</p>
                    </div>
                </div>

                {{-- Tanda Khusus --}}
                <div class="mb-4">
                    <label for="tanda_khusus" class="block text-sm font-medium text-slate-700 mb-2">
                        Tanda Khusus
                    </label>
                    <textarea name="tanda_khusus" 
                              id="tanda_khusus" 
                              rows="2"
                              placeholder="Ciri-ciri fisik yang membedakan, seperti bercak, bekas luka, dll"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">{{ old('tanda_khusus') }}</textarea>
                </div>

                {{-- Riwayat Alergi --}}
                <div class="mb-4">
                    <label for="riwayat_alergi" class="block text-sm font-medium text-slate-700 mb-2">
                        Riwayat Alergi
                    </label>
                    <textarea name="riwayat_alergi" 
                              id="riwayat_alergi" 
                              rows="2"
                              placeholder="Alergi obat, makanan, atau kondisi tertentu"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">{{ old('riwayat_alergi') }}</textarea>
                </div>

                {{-- Catatan --}}
                <div class="mb-4">
                    <label for="catatan" class="block text-sm font-medium text-slate-700 mb-2">
                        Catatan Lain
                    </label>
                    <textarea name="catatan" 
                              id="catatan" 
                              rows="2"
                              placeholder="Informasi tambahan tentang hewan ini"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">{{ old('catatan') }}</textarea>
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-center gap-3">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-slate-300 text-[#970747] focus:ring-[#970747]">
                    <label for="is_active" class="text-sm text-slate-700">
                        Hewan aktif (masih hidup / masih dipelihara)
                    </label>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ isset($selectedOwner) ? route('owners.show', $selectedOwner->id) : route('animals.index') }}" 
                   class="px-6 py-3 text-slate-600 hover:text-slate-800 font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-[#970747] to-purple-600 hover:from-[#7a0538] hover:to-purple-700 text-white rounded-xl font-semibold shadow-lg shadow-pink-200 transition-all">
                    💾 Simpan Hewan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
