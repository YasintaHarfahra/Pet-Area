{{-- 
=====================================================
VIEW: ANIMAL-RECORDS/EDIT.BLADE.PHP
=====================================================

Form untuk MENGEDIT REKAM MEDIS.
Struktur sama dengan create tapi dengan data existing.
Hewan tidak bisa diubah.

Digunakan oleh: AnimalMedicalRecordController@edit
=====================================================
--}}

@extends('layouts.admin_app')

@section('title', 'Edit Rekam Medis')
@section('header_title', 'Edit Rekam Medis')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Tombol Kembali --}}
    <a href="{{ route('animal-records.show', $record->id) }}" 
       class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 mb-6 group">
        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Detail
    </a>

    <form action="{{ route('animal-records.update', $record->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Error Display --}}
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ================================ --}}
        {{-- SECTION 1: INFO HEWAN (READ-ONLY) --}}
        {{-- ================================ --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-amber-50 to-orange-50">
                <div class="flex items-center gap-3">
                    <p class="font-mono text-sm text-slate-500">{{ $record->nomor_rm }}</p>
                    <span class="text-slate-300">•</span>
                    <h2 class="text-lg font-bold text-slate-800">✏️ Edit Rekam Medis</h2>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 bg-slate-50">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center text-3xl">
                        {{ $record->animal->species_emoji }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-lg">{{ $record->animal->nama_hewan }}</p>
                        <p class="text-sm text-slate-500">{{ $record->animal->jenis }} • Pemilik: {{ $record->animal->owner->nama }}</p>
                    </div>
                    <span class="ml-auto text-xs text-slate-400 italic">Tidak dapat diubah</span>
                </div>

                {{-- Alergi Warning --}}
                @if($record->animal->riwayat_alergi)
                    <div class="mt-4 p-3 rounded-lg bg-red-50 border border-red-200">
                        <p class="text-sm text-red-700 font-medium">
                            ⚠️ ALERGI: {{ $record->animal->riwayat_alergi }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ================================ --}}
        {{-- SECTION 2: KELUHAN & PEMERIKSAAN --}}
        {{-- ================================ --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">📝 Keluhan & Pemeriksaan</h2>
            </div>
            <div class="p-6 space-y-4">
                {{-- Tanggal & Status --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_periksa" class="block text-sm font-medium text-slate-700 mb-2">
                            Tanggal Periksa <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="tanggal_periksa" 
                               id="tanggal_periksa" 
                               value="{{ old('tanggal_periksa', $record->tanggal_periksa->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all"
                               required>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                        <select name="status" id="status"
                                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                            <option value="pemeriksaan" {{ old('status', $record->status) == 'pemeriksaan' ? 'selected' : '' }}>Pemeriksaan</option>
                            <option value="dalam_perawatan" {{ old('status', $record->status) == 'dalam_perawatan' ? 'selected' : '' }}>Dalam Perawatan</option>
                            <option value="selesai" {{ old('status', $record->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>

                {{-- Keluhan --}}
                <div>
                    <label for="keluhan" class="block text-sm font-medium text-slate-700 mb-2">
                        Keluhan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keluhan" id="keluhan" rows="3" required
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">{{ old('keluhan', $record->keluhan) }}</textarea>
                </div>

                {{-- Hasil Pemeriksaan --}}
                <div>
                    <label for="hasil_pemeriksaan" class="block text-sm font-medium text-slate-700 mb-2">
                        Hasil Pemeriksaan Fisik
                    </label>
                    <textarea name="hasil_pemeriksaan" id="hasil_pemeriksaan" rows="3"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">{{ old('hasil_pemeriksaan', $record->hasil_pemeriksaan) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ================================ --}}
        {{-- SECTION 3: VITAL SIGNS --}}
        {{-- ================================ --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">💓 Tanda Vital</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label for="suhu_tubuh" class="block text-sm font-medium text-slate-700 mb-2">Suhu (°C)</label>
                        <input type="number" name="suhu_tubuh" id="suhu_tubuh" 
                               value="{{ old('suhu_tubuh', $record->suhu_tubuh) }}"
                               step="0.1"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                    </div>
                    <div>
                        <label for="berat_badan" class="block text-sm font-medium text-slate-700 mb-2">Berat (kg)</label>
                        <input type="number" name="berat_badan" id="berat_badan" 
                               value="{{ old('berat_badan', $record->berat_badan) }}"
                               step="0.1"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                    </div>
                    <div>
                        <label for="detak_jantung" class="block text-sm font-medium text-slate-700 mb-2">Detak Jantung</label>
                        <input type="text" name="detak_jantung" id="detak_jantung" 
                               value="{{ old('detak_jantung', $record->detak_jantung) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                    </div>
                    <div>
                        <label for="respirasi" class="block text-sm font-medium text-slate-700 mb-2">Respirasi</label>
                        <input type="text" name="respirasi" id="respirasi" 
                               value="{{ old('respirasi', $record->respirasi) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================ --}}
        {{-- SECTION 4: DIAGNOSA & TINDAKAN --}}
        {{-- ================================ --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">🩺 Diagnosa & Tindakan</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label for="diagnosa" class="block text-sm font-medium text-slate-700 mb-2">Diagnosa</label>
                    <textarea name="diagnosa" id="diagnosa" rows="2"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">{{ old('diagnosa', $record->diagnosa) }}</textarea>
                </div>
                <div>
                    <label for="tindakan" class="block text-sm font-medium text-slate-700 mb-2">Tindakan</label>
                    <textarea name="tindakan" id="tindakan" rows="2"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">{{ old('tindakan', $record->tindakan) }}</textarea>
                </div>
                <div>
                    <label for="obat" class="block text-sm font-medium text-slate-700 mb-2">💊 Obat / Resep</label>
                    <textarea name="obat" id="obat" rows="3"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">{{ old('obat', $record->obat) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ================================ --}}
        {{-- SECTION 5: BIAYA --}}
        {{-- ================================ --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">💰 Biaya</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="biaya_pemeriksaan" class="block text-sm font-medium text-slate-700 mb-2">Biaya Pemeriksaan</label>
                        <input type="number" name="biaya_pemeriksaan" id="biaya_pemeriksaan" 
                               value="{{ old('biaya_pemeriksaan', $record->biaya_pemeriksaan) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                    </div>
                    <div>
                        <label for="biaya_obat" class="block text-sm font-medium text-slate-700 mb-2">Biaya Obat</label>
                        <input type="number" name="biaya_obat" id="biaya_obat" 
                               value="{{ old('biaya_obat', $record->biaya_obat) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                    </div>
                    <div>
                        <label for="biaya_tindakan" class="block text-sm font-medium text-slate-700 mb-2">Biaya Tindakan</label>
                        <input type="number" name="biaya_tindakan" id="biaya_tindakan" 
                               value="{{ old('biaya_tindakan', $record->biaya_tindakan) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================ --}}
        {{-- SECTION 6: KONTROL --}}
        {{-- ================================ --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">📅 Jadwal Kontrol</h2>
            </div>
            <div class="p-6">
                <div>
                    <label for="tanggal_kontrol" class="block text-sm font-medium text-slate-700 mb-2">
                        Tanggal Kontrol Berikutnya
                    </label>
                    <input type="date" name="tanggal_kontrol" id="tanggal_kontrol" 
                           value="{{ old('tanggal_kontrol', $record->tanggal_kontrol?->format('Y-m-d')) }}"
                           class="w-full md:w-1/2 px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                </div>
            </div>
        </div>

        {{-- Tombol Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('animal-records.show', $record->id) }}" 
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
@endsection
