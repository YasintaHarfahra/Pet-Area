@extends('layouts.dokter_app')

@section('title', 'Edit Rekam Medis')

@push('styles')
    {{-- Library untuk Dropdown Searchable --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control { border-radius: 0.5rem !important; padding: 0.6rem !important; border: 1px solid #d1d5db !important; }
        .ts-dropdown { z-index: 9999 !important; position: absolute !important; }
        input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .card-with-select { overflow: visible !important; z-index: 20; position: relative; }
        
        /* Gaya Tombol Plus (+) Minimalis */
        .btn-plus-action {
            width: 24px; height: 24px;
            display: flex; align-items: center; justify-content: center;
            background-color: #970747; color: white;
            border-radius: 999px; transition: all 0.2s;
            border: none; cursor: pointer;
        }
        .btn-plus-action:hover { background-color: #7a0538; transform: scale(1.1); }
    </style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <form action="{{ route('dokter.riwayat.update', $record->id_medical_record) }}" method="POST" id="formRekamMedis" class="space-y-6">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-bold mb-1">Data tidak bisa disimpan:</p>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="prescriptionStockAlert" class="hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

        {{-- 1. Tombol Kembali --}}
        <div class="mb-4">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-[#970747] transition group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                KEMBALI
            </a>
        </div>

        {{-- 2. Header & Informasi Pasien --}}
        <div class="mb-8 border-b pb-6">
            <h1 class="text-2xl font-bold text-gray-800">Pembaruan Rekam Medis</h1>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-2 text-sm bg-gray-50 p-4 rounded-xl border border-gray-100">
                <div class="flex gap-2">
                    <span class="text-gray-400 font-medium w-24">Pasien</span>
                    <span class="font-bold text-gray-800">: {{ $record->pet->nama_hewan ?? '-' }}</span>
                </div>
                <div class="flex gap-2">
                    <span class="text-gray-400 font-medium w-24">Pemilik</span>
                    <span class="font-bold text-gray-800">: {{ $record->pet->owner->nama ?? '-' }}</span>
                </div>
                <div class="flex gap-2">
                    <span class="text-gray-400 font-medium w-24">Umur</span>
                    @php 
                        $tglLahir = $record->pet->tgl_lahir ?? null;
                        $umur = "-";
                        if($tglLahir) {
                            $dob = \Carbon\Carbon::parse($tglLahir);
                            $diff = $dob->diff(\Carbon\Carbon::now());
                            $umur = $diff->y . " Tahun " . $diff->m . " Bulan";
                        }
                    @endphp
                    <span class="font-bold text-gray-800">: {{ $umur }}</span>
                </div>
                <div class="flex gap-2">
                    <span class="text-gray-400 font-medium w-24">No. RM</span>
                    <span class="font-mono font-bold text-[#970747]">: {{ $record->pet->no_rm }}</span>
                </div>
            </div>
        </div>

        {{-- 3. Hasil Pemeriksaan & Tindakan --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm card-with-select">
            <div class="p-4 bg-gray-50/50 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#970747]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Pemeriksaan & Tindakan</h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-600">Berat Badan (kg)</label>
                        <input type="number" name="berat_badan" step="0.1" value="{{ old('berat_badan', $record->berat_badan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none focus:border-[#970747]">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-600">Suhu Tubuh (°C)</label>
                        <input type="number" name="suhu_tubuh" step="0.1" value="{{ old('suhu_tubuh', $record->suhu_tubuh) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none focus:border-[#970747]">
                    </div>
                </div>

                {{-- PERBAIKAN: Menggunakan name "symptoms" (Inggris) dengan Fallback --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-600">Keluhan</label>
                    <textarea name="symptoms" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none focus:border-[#970747]">{{ old('symptoms', $record->symptoms ?? $record->keluhan) }}</textarea>
                </div>

                {{-- PERBAIKAN: Menggunakan name "diagnosis" (Inggris) dengan Fallback --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-600">Diagnosa <span class="text-red-500">*</span></label>
                    <textarea name="diagnosis" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none focus:border-[#970747] font-medium">{{ old('diagnosis', $record->diagnosis ?? $record->diagnosa) }}</textarea>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center gap-2 mb-1">
                        <label class="text-sm font-bold text-[#970747]">Tindakan Medis</label>
                        <button type="button" id="btnAddTreatment" class="btn-plus-action"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg></button>
                    </div>
                    <select id="tindakan_select" name="tindakan_id[]" multiple placeholder="Pilih tindakan...">
                        @php
                            $ids = $record->treatments ? $record->treatments->pluck('id_treatment')->toArray() : [];
                            $finalIds = array_map('intval', array_filter((array)$ids));
                            $selectedT = old('tindakan_id', $finalIds);
                            $kategoriLabels = [
                                'vaksin' => 'Vaksin',
                                'konsultasi' => 'Konsultasi',
                                'perawatan' => 'Perawatan',
                                'operasi' => 'Operasi',
                                'pemeriksaan_khusus' => 'Pemeriksaan Khusus',
                                'obat_injeksi' => 'Obat & Injeksi',
                            ];
                            $grouped = $tindakans->groupBy(fn($t) => $t->kategori ?? 'lainnya');
                        @endphp
                        @foreach($grouped as $kat => $items)
                            <optgroup label="{{ $kategoriLabels[$kat] ?? ucfirst(str_replace('_', ' ', $kat)) }}">
                                @foreach($items as $t)
                                    <option value="{{ $t->id_treatment }}" {{ in_array($t->id_treatment, $selectedT) ? 'selected' : '' }}>
                                        {{ $t->nama_tindakan }} - Rp{{ number_format($t->harga, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- 4. Resep Obat --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden card-with-select">
            <div class="p-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-sm font-bold text-blue-600 uppercase tracking-wider">Resep Obat</h2>
                <button type="button" onclick="addRow()" class="text-xs font-black text-blue-600 hover:text-blue-800 transition uppercase">+ Tambah Obat</button>
            </div>
            <div class="p-0 overflow-visible">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left bg-gray-50/50 text-gray-500 border-b border-gray-100">
                            <th class="px-6 py-3 font-semibold">Nama Obat</th>
                            <th class="px-6 py-3 font-semibold text-center w-32">Jumlah</th>
                            <th class="px-6 py-3 font-semibold">Aturan Pakai</th>
                            <th class="px-6 py-3 text-center w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="resepTableBody">
                        @forelse($record->prescriptionItems as $i => $item)
                        <tr class="row-resep hover:bg-gray-50/30 transition">
                            <td class="px-4 py-3">
                                <select name="prescriptions[{{ $i }}][id_medicine]" class="medicine-select w-full">
                                    @foreach($medicines as $m)
                                        <option value="{{ $m->id_medicine }}" {{ $item->id_medicine == $m->id_medicine ? 'selected' : '' }}>{{ $m->nama_obat }} ({{ $m->satuan }}) - Stok: {{ $m->stok }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="prescriptions[{{ $i }}][jumlah]" value="{{ $item->jumlah }}" min="1" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-center font-bold text-blue-600 outline-none">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" name="prescriptions[{{ $i }}][instruksi]" value="{{ $item->instruksi }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg outline-none">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" onclick="removeRow(this)" class="text-red-300 hover:text-red-600 transition text-xl">&times;</button>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow"><td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">Belum ada resep.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 5. Rujukan & Kontrol --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-sm font-bold text-orange-600 uppercase tracking-wider mb-4">Surat Rujukan</h2>
                <div class="space-y-4">
                    <input type="text" name="tujuan_rujukan" value="{{ old('tujuan_rujukan', $record->referral->tujuan_rujukan ?? '') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:border-orange-500 outline-none transition" placeholder="Instansi Tujuan">
                    <textarea name="alasan_rujukan" rows="2" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:border-orange-500 outline-none transition" placeholder="Alasan Rujukan">{{ old('alasan_rujukan', $record->referral->alasan_rujukan ?? '') }}</textarea>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-4">Rencana Kontrol</h2>
                <div class="space-y-4">
                    {{-- PERBAIKAN: Menggunakan name "next_control_date" (Inggris) --}}
                    <input type="date" name="next_control_date" value="{{ old('next_control_date', $record->next_control_date ? \Carbon\Carbon::parse($record->next_control_date)->format('Y-m-d') : ($record->tgl_kontrol_berikutnya ? $record->tgl_kontrol_berikutnya->format('Y-m-d') : '')) }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 outline-none transition">
                    {{-- PERBAIKAN: Menggunakan name "follow_up_notes" (Inggris) --}}
                    <textarea name="follow_up_notes" rows="2" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 outline-none transition" placeholder="Catatan Kontrol">{{ old('follow_up_notes', $record->follow_up_notes ?? $record->catatan_kontrol) }}</textarea>
                </div>
            </div>
        </div>

        <div class="pt-10 flex items-center justify-center gap-4 border-t">
            <a href="{{ route('dokter.riwayat.index') }}" class="px-8 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition">BATAL</a>
            <button type="submit" class="px-10 py-3 bg-[#970747] text-white rounded-xl font-bold shadow-xl hover:bg-[#7a0538] transition transform active:scale-95">SIMPAN PERUBAHAN</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        let tControl;
        document.addEventListener("DOMContentLoaded", function() {
            tControl = new TomSelect("#tindakan_select", {
                plugins: ['remove_button', 'optgroup_columns'],
                create: false,
                dropdownParent: 'body',
                optgroupField: 'optgroup',
                lockOptgroupOrder: true
            });
            document.querySelectorAll('.medicine-select').forEach(el => { new TomSelect(el, { create: false, dropdownParent: 'body' }); });

            // SweetAlert Tambah Tindakan
            document.getElementById('btnAddTreatment').addEventListener('click', function() {
                Swal.fire({
                    title: '<span style="font-size:1.1rem;font-weight:600;color:#970747;">Tindakan Baru</span>',
                    html: `<div style="text-align:left;padding:0.5rem 0;">
                        <div style="margin-bottom:1.25rem;">
                            <label style="display:block;font-size:0.875rem;font-weight:600;color:#6B7280;margin-bottom:0.5rem;">Nama Tindakan <span style="color:#EF4444;">*</span></label>
                            <input id="swal-n" type="text" placeholder="cth: USG" style="width:100%;padding:0.625rem;border:1px solid #E5E7EB;border-radius:0.375rem;font-size:0.95rem;box-sizing:border-box;" />
                        </div>
                        <div style="margin-bottom:1.25rem;">
                            <label style="display:block;font-size:0.875rem;font-weight:600;color:#6B7280;margin-bottom:0.5rem;">Kategori</label>
                            <select id="swal-k" style="width:100%;padding:0.625rem;border:1px solid #E5E7EB;border-radius:0.375rem;font-size:0.95rem;box-sizing:border-box;">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="vaksin">Vaksin</option>
                                <option value="konsultasi">Konsultasi</option>
                                <option value="perawatan">Perawatan</option>
                                <option value="operasi">Operasi</option>
                                <option value="pemeriksaan_khusus">Pemeriksaan Khusus</option>
                                <option value="obat_injeksi">Obat & Injeksi</option>
                            </select>
                        </div>
                        <div style="margin-bottom:0;">
                            <label style="display:block;font-size:0.875rem;font-weight:600;color:#6B7280;margin-bottom:0.5rem;">Harga (Rp) <span style="color:#EF4444;">*</span></label>
                            <input id="swal-p" type="number" placeholder="0" style="width:100%;padding:0.625rem;border:1px solid #E5E7EB;border-radius:0.375rem;font-size:0.95rem;box-sizing:border-box;" />
                        </div>
                    </div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Simpan ke Database',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#970747',
                    cancelButtonColor: '#9CA3AF',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        const n = document.getElementById('swal-n').value;
                        const k = document.getElementById('swal-k').value;
                        const p = document.getElementById('swal-p').value;
                        if (!n || !p) { Swal.showValidationMessage('Nama & Harga wajib diisi!'); return false; }
                        return fetch("{{ route('dokter.api.treatments.store') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ nama_tindakan: n, harga: p, kategori: k || null })
                        }).then(r => r.ok ? r.json() : Promise.reject('Gagal menyimpan.')).catch(e => Swal.showValidationMessage(e));
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed && result.value.success) {
                        const data = result.value.treatment;
                        tControl.addOption({ value: data.id, text: data.nama_tindakan + ' - Rp' + parseInt(data.harga).toLocaleString('id-ID') });
                        tControl.addItem(data.id);
                        Swal.fire('Berhasil!', 'Tindakan tersimpan.', 'success');
                    }
                });
            });
        });

        let rowIndex = {{ count($record->prescriptionItems) }};
        const medicineMeta = @json($medicines->mapWithKeys(fn($m) => [
            (string) $m->id_medicine => [
                'nama' => $m->nama_obat,
                'stok' => (int) $m->stok,
            ]
        ]));
        const originalPrescriptionQty = @json($record->prescriptionItems->groupBy('id_medicine')->map(fn($items) => (int) $items->sum('jumlah')));

        function validatePrescriptionStockEdit() {
            const alertBox = document.getElementById('prescriptionStockAlert');
            if (!alertBox) return true;

            const requested = {};
            document.querySelectorAll('#resepTableBody .row-resep').forEach(function(row) {
                const medSelect = row.querySelector('select[name*="[id_medicine]"]');
                const qtyInput = row.querySelector('input[name*="[jumlah]"]');
                const medId = medSelect ? String(medSelect.value || '') : '';
                const qty = qtyInput ? parseInt(qtyInput.value || '0', 10) : 0;
                if (!medId || Number.isNaN(qty) || qty <= 0) return;
                requested[medId] = (requested[medId] || 0) + qty;
            });

            const issues = [];
            Object.keys(requested).forEach(function(medId) {
                const meta = medicineMeta[medId];
                if (!meta) return;

                const currentStock = parseInt(meta.stok || 0, 10);
                const maxAllowed = currentStock;
                if (requested[medId] > maxAllowed) {
                    issues.push(`Jumlah ${meta.nama} melebihi stok. Maksimal ${maxAllowed}, diminta ${requested[medId]}.`);
                }
            });

            if (issues.length > 0) {
                alertBox.textContent = issues[0];
                alertBox.classList.remove('hidden');
                return false;
            }

            alertBox.textContent = '';
            alertBox.classList.add('hidden');
            return true;
        }

        function addRow() {
            const emptyRow = document.getElementById('emptyRow'); if (emptyRow) emptyRow.remove();
            const tbody = document.getElementById('resepTableBody');
            const row = document.createElement('tr');
            row.className = 'row-resep hover:bg-gray-50/30 transition';
            row.innerHTML = `<td class="px-4 py-3"><select name="prescriptions[${rowIndex}][id_medicine]" class="med-new w-full"><option value="">-- Pilih --</option>@foreach($medicines as $m)<option value="{{ $m->id_medicine }}">{{ $m->nama_obat }} ({{ $m->satuan }}) - Stok: {{ $m->stok }}</option>@endforeach</select></td><td class="px-4 py-3 text-center"><input type="number" name="prescriptions[${rowIndex}][jumlah]" min="1" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-center font-bold text-blue-600 outline-none"></td><td class="px-4 py-3"><input type="text" name="prescriptions[${rowIndex}][instruksi]" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg outline-none"></td><td class="px-4 py-3 text-center"><button type="button" onclick="removeRow(this)" class="text-red-300 hover:text-red-600 transition text-xl">&times;</button></td>`;
            tbody.appendChild(row);
            new TomSelect(row.querySelector('.med-new'), { create: false, dropdownParent: 'body' });
            rowIndex++;
            validatePrescriptionStockEdit();
        }
        function removeRow(btn) { 
            btn.closest('tr').remove(); 
            if (document.querySelectorAll('.row-resep').length === 0) {
                document.getElementById('resepTableBody').innerHTML = '<tr id="emptyRow"><td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">Belum ada resep.</td></tr>';
            }
            validatePrescriptionStockEdit();
        }

        document.getElementById('formRekamMedis').addEventListener('input', function(e) {
            if (e.target && (e.target.name || '').includes('prescriptions')) {
                validatePrescriptionStockEdit();
            }
        });

        document.getElementById('formRekamMedis').addEventListener('submit', function(e) {
            if (!validatePrescriptionStockEdit()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok obat tidak cukup',
                    text: 'Periksa kembali jumlah resep karena melebihi stok tersedia.',
                    confirmButtonColor: '#970747'
                });
            }
        });

        validatePrescriptionStockEdit();
    </script>
@endpush