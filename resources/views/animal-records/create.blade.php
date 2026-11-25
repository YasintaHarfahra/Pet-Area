{{-- 
=====================================================
VIEW: ANIMAL-RECORDS/CREATE.BLADE.PHP
=====================================================

Form untuk MEMBUAT REKAM MEDIS BARU.
Harus dikaitkan dengan hewan yang sudah ada.
Jika akses dari halaman detail hewan, animal_id sudah di-prefill.

Form ini cukup panjang karena mencakup:
- Info hewan (auto-fill)
- Keluhan & Hasil Pemeriksaan
- Diagnosa & Tindakan
- Obat
- Vital Signs
- Biaya
- Jadwal Kontrol

Digunakan oleh: AnimalMedicalRecordController@create
=====================================================
--}}

@extends('layouts.admin_app')

@section('title', 'Buat Rekam Medis Baru')
@section('header_title', 'Rekam Medis Baru')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Tombol Kembali --}}
    @if(isset($selectedAnimal))
        <a href="{{ route('animals.show', $selectedAnimal->id) }}" 
           class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 mb-6 group">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke {{ $selectedAnimal->nama_hewan }}
        </a>
    @else
        <a href="{{ route('animal-records.index') }}" 
           class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 mb-6 group">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar
        </a>
    @endif

    {{-- Form Card --}}
    <form action="{{ route('animal-records.store') }}" method="POST">
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
        {{-- SECTION 1: PILIH HEWAN --}}
        {{-- ================================ --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-pink-50 to-purple-50">
                <h2 class="text-lg font-bold text-slate-800">🐾 Data Pasien</h2>
            </div>
            <div class="p-6">
                @if(isset($selectedAnimal))
                    {{-- Hewan sudah dipilih --}}
                    <input type="hidden" name="animal_id" value="{{ $selectedAnimal->id }}">
                    <div class="flex items-center gap-4 p-4 rounded-xl border-2 border-pink-200 bg-pink-50">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center text-4xl">
                            {{ $selectedAnimal->species_emoji }}
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-800 text-lg">{{ $selectedAnimal->nama_hewan }}</p>
                            <p class="text-sm text-slate-500">{{ $selectedAnimal->jenis }} {{ $selectedAnimal->ras ? '• ' . $selectedAnimal->ras : '' }}</p>
                            <p class="text-sm text-slate-400 mt-1">
                                Pemilik: <span class="font-medium text-emerald-600">{{ $selectedAnimal->owner->nama }}</span>
                                ({{ $selectedAnimal->owner->no_hp }})
                            </p>
                        </div>
                        <a href="{{ route('animal-records.create') }}" 
                           class="text-sm text-[#970747] hover:underline">
                            Ganti Hewan
                        </a>
                    </div>

                    {{-- Tampilkan alergi jika ada --}}
                    @if($selectedAnimal->riwayat_alergi)
                        <div class="mt-4 p-3 rounded-lg bg-red-50 border border-red-200">
                            <p class="text-sm text-red-700 font-medium">
                                ⚠️ ALERGI: {{ $selectedAnimal->riwayat_alergi }}
                            </p>
                        </div>
                    @endif
                @else
                    {{-- Pilih hewan dengan search --}}
                    <div x-data="{ search: '', showResults: false, selectedAnimal: null }" class="relative">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Pilih Hewan <span class="text-red-500">*</span>
                        </label>
                        <select name="animal_id" 
                                id="animal_id"
                                class="w-full px-4 py-3 rounded-xl border {{ $errors->has('animal_id') ? 'border-red-500' : 'border-slate-300' }} focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all"
                                required>
                            <option value="">-- Pilih Hewan --</option>
                            @foreach($animals as $animal)
                                <option value="{{ $animal->id }}" {{ old('animal_id') == $animal->id ? 'selected' : '' }}>
                                    {{ $animal->nama_hewan }} ({{ $animal->jenis }}) - Pemilik: {{ $animal->owner->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('animal_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <p class="text-xs text-slate-400 mt-2">
                            Hewan belum terdaftar? 
                            <a href="{{ route('animals.create') }}" class="text-[#970747] hover:underline">Daftarkan hewan baru</a>
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
                {{-- Tanggal Periksa --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_periksa" class="block text-sm font-medium text-slate-700 mb-2">
                            Tanggal Periksa <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="tanggal_periksa" 
                               id="tanggal_periksa" 
                               value="{{ old('tanggal_periksa', now()->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all"
                               required>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-2">
                            Status
                        </label>
                        <select name="status" id="status"
                                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                            <option value="pemeriksaan" {{ old('status') == 'pemeriksaan' ? 'selected' : '' }}>Pemeriksaan</option>
                            <option value="dalam_perawatan" {{ old('status') == 'dalam_perawatan' ? 'selected' : '' }}>Dalam Perawatan</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>

                {{-- Keluhan --}}
                <div>
                    <label for="keluhan" class="block text-sm font-medium text-slate-700 mb-2">
                        Keluhan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keluhan" id="keluhan" rows="3" required
                              placeholder="Keluhan yang disampaikan pemilik..."
                              class="w-full px-4 py-3 rounded-xl border {{ $errors->has('keluhan') ? 'border-red-500' : 'border-slate-300' }} focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">{{ old('keluhan') }}</textarea>
                    @error('keluhan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Hasil Pemeriksaan --}}
                <div>
                    <label for="hasil_pemeriksaan" class="block text-sm font-medium text-slate-700 mb-2">
                        Hasil Pemeriksaan Fisik
                    </label>
                    <textarea name="hasil_pemeriksaan" id="hasil_pemeriksaan" rows="3"
                              placeholder="Hasil pemeriksaan fisik oleh dokter..."
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">{{ old('hasil_pemeriksaan') }}</textarea>
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
                        <input type="number" name="suhu_tubuh" id="suhu_tubuh" value="{{ old('suhu_tubuh') }}"
                               step="0.1" placeholder="38.5"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                    </div>
                    <div>
                        <label for="berat_badan" class="block text-sm font-medium text-slate-700 mb-2">Berat (kg)</label>
                        <input type="number" name="berat_badan" id="berat_badan" value="{{ old('berat_badan') }}"
                               step="0.1" placeholder="5.0"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                    </div>
                    <div>
                        <label for="detak_jantung" class="block text-sm font-medium text-slate-700 mb-2">Detak Jantung</label>
                        <input type="text" name="detak_jantung" id="detak_jantung" value="{{ old('detak_jantung') }}"
                               placeholder="120 bpm"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                    </div>
                    <div>
                        <label for="respirasi" class="block text-sm font-medium text-slate-700 mb-2">Respirasi</label>
                        <input type="text" name="respirasi" id="respirasi" value="{{ old('respirasi') }}"
                               placeholder="20/menit"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
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
                {{-- Diagnosa --}}
                <div>
                    <label for="diagnosa" class="block text-sm font-medium text-slate-700 mb-2">Diagnosa</label>
                    <textarea name="diagnosa" id="diagnosa" rows="2"
                              placeholder="Diagnosa penyakit..."
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">{{ old('diagnosa') }}</textarea>
                </div>

                {{-- Tindakan --}}
                <div>
                    <label for="tindakan" class="block text-sm font-medium text-slate-700 mb-2">Tindakan</label>
                    <textarea name="tindakan" id="tindakan" rows="2"
                              placeholder="Tindakan yang dilakukan (suntik, operasi, dll)..."
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">{{ old('tindakan') }}</textarea>
                </div>

                {{-- Obat (catatan manual) --}}
                <div>
                    <label for="obat" class="block text-sm font-medium text-slate-700 mb-2">Catatan Obat (Manual)</label>
                    <textarea name="obat" id="obat" rows="2"
                              placeholder="Catatan tambahan tentang obat..."
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">{{ old('obat') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ================================ --}}
        {{-- SECTION: RESEP OBAT (BARU) --}}
        {{-- ================================ --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-teal-50 to-emerald-50">
                <h2 class="text-lg font-bold text-slate-800">💊 Resep Obat</h2>
                <p class="text-sm text-slate-500">Pilih obat dari stok klinik (stok akan berkurang otomatis)</p>
            </div>
            <div class="p-6">
                <div id="prescriptions-container" class="space-y-4">
                    {{-- Template row akan di-clone oleh JavaScript --}}
                </div>
                
                <button type="button" onclick="addPrescriptionRow()" 
                        class="mt-4 flex items-center gap-2 px-4 py-2 text-teal-600 hover:bg-teal-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Obat
                </button>

                {{-- Invoice Preview --}}
                <div id="prescriptions-total" class="mt-4 p-4 rounded-xl bg-teal-50 border border-teal-200 hidden">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-slate-700">Total Biaya Obat:</span>
                        <span id="total-obat-display" class="text-lg font-bold text-teal-700">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================ --}}
        {{-- SECTION: RUJUKAN (BARU) --}}
        {{-- ================================ --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-orange-50 to-amber-50">
                <h2 class="text-lg font-bold text-slate-800">📄 Surat Rujukan</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <input type="checkbox" id="buat_rujukan" name="buat_rujukan" value="1"
                           onchange="toggleRujukanForm()"
                           class="w-5 h-5 rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                    <label for="buat_rujukan" class="font-medium text-slate-700">
                        Buat Surat Rujukan untuk pasien ini
                    </label>
                </div>
                
                <div id="rujukan-form" class="hidden space-y-4 pt-4 border-t border-slate-200">
                    <div>
                        <label for="tujuan_rujukan" class="block text-sm font-medium text-slate-700 mb-2">
                            Tujuan Rujukan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="tujuan_rujukan" id="tujuan_rujukan" 
                               value="{{ old('tujuan_rujukan') }}"
                               placeholder="Nama klinik/rumah sakit hewan tujuan"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all">
                    </div>
                    <div>
                        <label for="alasan_rujukan" class="block text-sm font-medium text-slate-700 mb-2">
                            Alasan Rujukan
                        </label>
                        <textarea name="alasan_rujukan" id="alasan_rujukan" rows="3"
                                  placeholder="Alasan merujuk pasien (perlu pemeriksaan khusus, fasilitas tidak tersedia, dll)"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all">{{ old('alasan_rujukan') }}</textarea>
                    </div>
                    <div class="p-3 rounded-lg bg-amber-50 border border-amber-200">
                        <p class="text-sm text-amber-700">
                            ⚠️ Surat rujukan akan disimpan dan bisa dicetak oleh Admin di menu "Surat Rujukan"
                        </p>
                    </div>
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
                               value="{{ old('biaya_pemeriksaan', 0) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                    </div>
                    <div>
                        <label for="biaya_obat" class="block text-sm font-medium text-slate-700 mb-2">Biaya Obat</label>
                        <input type="number" name="biaya_obat" id="biaya_obat" 
                               value="{{ old('biaya_obat', 0) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                    </div>
                    <div>
                        <label for="biaya_tindakan" class="block text-sm font-medium text-slate-700 mb-2">Biaya Tindakan</label>
                        <input type="number" name="biaya_tindakan" id="biaya_tindakan" 
                               value="{{ old('biaya_tindakan', 0) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
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
                           value="{{ old('tanggal_kontrol') }}"
                           class="w-full md:w-1/2 px-4 py-3 rounded-xl border border-slate-300 focus:border-[#970747] focus:ring-2 focus:ring-pink-200 transition-all">
                    <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak perlu kontrol</p>
                </div>
            </div>
        </div>

        {{-- Tombol Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ isset($selectedAnimal) ? route('animals.show', $selectedAnimal->id) : route('animal-records.index') }}" 
               class="px-6 py-3 text-slate-600 hover:text-slate-800 font-medium transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-8 py-3 bg-gradient-to-r from-[#970747] to-purple-600 hover:from-[#7a0538] hover:to-purple-700 text-white rounded-xl font-semibold shadow-lg shadow-pink-200 transition-all">
                💾 Simpan Rekam Medis
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script type="application/json" id="medicines-data">{!! json_encode($medicines ?? []) !!}</script>
<script>
    // Data obat dari server
    const medicines = JSON.parse(document.getElementById('medicines-data').textContent);
    let prescriptionIndex = 0;

    // Tambah baris resep obat
    function addPrescriptionRow() {
        const container = document.getElementById('prescriptions-container');
        const row = document.createElement('div');
        row.className = 'prescription-row flex gap-3 items-start p-4 rounded-xl bg-slate-50 border border-slate-200';
        row.innerHTML = `
            <div class="flex-1">
                <label class="block text-xs font-medium text-slate-600 mb-1">Pilih Obat</label>
                <select name="prescriptions[${prescriptionIndex}][medicine_id]" 
                        onchange="updateMedicineInfo(this, ${prescriptionIndex})"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 bg-white text-sm">
                    <option value="">-- Pilih Obat --</option>
                    ${medicines.map(m => `
                        <option value="${m.id}" 
                                data-harga="${m.harga_jual}" 
                                data-stok="${m.stok}"
                                data-satuan="${m.satuan}">
                            ${m.nama_obat} (${m.satuan}) - Stok: ${m.stok}
                        </option>
                    `).join('')}
                </select>
            </div>
            <div class="w-24">
                <label class="block text-xs font-medium text-slate-600 mb-1">Jumlah</label>
                <input type="number" name="prescriptions[${prescriptionIndex}][jumlah]" 
                       value="1" min="1"
                       onchange="calculatePrescriptionTotal()"
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 text-sm">
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-slate-600 mb-1">Harga</label>
                <div class="medicine-price text-sm font-medium text-slate-700 py-2">-</div>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-medium text-slate-600 mb-1">Instruksi</label>
                <input type="text" name="prescriptions[${prescriptionIndex}][instruksi]" 
                       placeholder="3x1 sesudah makan"
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 text-sm">
            </div>
            <button type="button" onclick="removePrescriptionRow(this)" 
                    class="mt-6 p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        `;
        container.appendChild(row);
        prescriptionIndex++;
        document.getElementById('prescriptions-total').classList.remove('hidden');
    }

    // Update info obat saat dipilih
    function updateMedicineInfo(select, index) {
        const row = select.closest('.prescription-row');
        const priceDiv = row.querySelector('.medicine-price');
        const jumlahInput = row.querySelector('input[type="number"]');
        
        if (select.value) {
            const option = select.options[select.selectedIndex];
            const harga = parseFloat(option.dataset.harga);
            const stok = parseInt(option.dataset.stok);
            
            priceDiv.textContent = formatRupiah(harga);
            jumlahInput.max = stok;
            
            if (parseInt(jumlahInput.value) > stok) {
                jumlahInput.value = stok;
            }
        } else {
            priceDiv.textContent = '-';
        }
        
        calculatePrescriptionTotal();
    }

    // Hapus baris resep
    function removePrescriptionRow(button) {
        button.closest('.prescription-row').remove();
        calculatePrescriptionTotal();
        
        if (document.querySelectorAll('.prescription-row').length === 0) {
            document.getElementById('prescriptions-total').classList.add('hidden');
        }
    }

    // Hitung total biaya obat
    function calculatePrescriptionTotal() {
        let total = 0;
        document.querySelectorAll('.prescription-row').forEach(row => {
            const select = row.querySelector('select');
            const jumlahInput = row.querySelector('input[type="number"]');
            
            if (select.value) {
                const option = select.options[select.selectedIndex];
                const harga = parseFloat(option.dataset.harga);
                const jumlah = parseInt(jumlahInput.value) || 0;
                total += harga * jumlah;
            }
        });
        
        document.getElementById('total-obat-display').textContent = formatRupiah(total);
        document.getElementById('biaya_obat').value = total;
    }

    // Format angka ke Rupiah
    function formatRupiah(num) {
        return 'Rp ' + num.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Toggle form rujukan
    function toggleRujukanForm() {
        const checkbox = document.getElementById('buat_rujukan');
        const form = document.getElementById('rujukan-form');
        
        if (checkbox.checked) {
            form.classList.remove('hidden');
        } else {
            form.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
