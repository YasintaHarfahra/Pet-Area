@extends('layouts.dokter_app')

@section('title', 'Pemeriksaan Baru - Pet Area Clinic')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css">
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <a href="{{ route('dokter.antrian.index') }}" class="inline-flex items-center text-gray-600 hover:text-[#970747] mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
    <div class="mb-6">

    <form action="{{ route('dokter.examinations.store') }}" method="POST" id="examinationForm">
        @csrf
        @if(session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <input type="hidden" name="tgl_periksa" id="tgl_periksa_hidden" value="{{ now()->format('Y-m-d H:i:s') }}">
        @if(isset($queueData) && $queueData)
            <input type="hidden" name="queue" value="{{ $queueData->id_queue }}">
        @endif
        {{-- Draft pemeriksaan --}}
        @if(isset($draftData) && $draftData)
            <input type="hidden" id="draftDataJson" value='@json($draftData->form_data)'>
        @endif
        <div id="customTindakanInputs"></div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">

                {{-- Section 1: Data Pasien --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#970747]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Data Pasien
                    </h2>
                    @php
                        $activePet = isset($queueData) && $queueData ? $queueData->pet : ($petData ?? null);
                    @endphp
                    <div class="mb-4">
                        @if($activePet?->foto_hewan_url)
                            <img src="{{ $activePet->foto_hewan_url }}" alt="Foto {{ $activePet->nama_hewan }}" class="w-24 h-24 rounded-xl object-cover border border-gray-200">
                        @else
                            <div class="w-24 h-24 rounded-xl bg-pink-100 text-[#970747] text-3xl font-bold flex items-center justify-center">
                                {{ strtoupper(substr($activePet->nama_hewan ?? 'P', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Hewan</label>
                            <input type="text" class="w-full px-4 py-2 border rounded-lg bg-gray-100 font-bold" value="{{ isset($queueData) && $queueData ? $queueData->pet->nama_hewan : ($petData->nama_hewan ?? '-') }}" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik</label>
                            <input type="text" class="w-full px-4 py-2 border rounded-lg bg-gray-100 font-bold" value="{{ isset($queueData) && $queueData ? $queueData->pet->owner->nama : ($petData->owner->nama ?? '-') }}" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Umur</label>
                            @php
                                $tglLahir = isset($queueData) && $queueData ? $queueData->pet->tgl_lahir : ($petData->tgl_lahir ?? null);
                            @endphp
                            @if($tglLahir)
                                @php
                                    $dob = \Carbon\Carbon::parse($tglLahir);
                                    $diff = $dob->diff(\Carbon\Carbon::now());
                                @endphp
                                <input type="text" class="w-full px-4 py-2 border rounded-lg bg-gray-100" value="{{ $diff->y }}th {{ $diff->m }}bln" readonly>
                            @else
                                <input type="text" class="w-full px-4 py-2 border rounded-lg bg-gray-100" value="-" readonly>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="pet_id" value="{{ isset($queueData) && $queueData ? $queueData->pet->id_pet : ($petData->id_pet ?? '') }}">
                </div>

                {{-- Section 2: Tabel Riwayat Medis --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-100 backdrop-blur overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-4 sm:px-5 py-3 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">📋 Riwayat Pemeriksaan</h3>
                            <p class="text-xs text-slate-600 mt-0.5">Daftar pemeriksaan sebelumnya untuk referensi diagnosa</p>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-600">
                            <div class="text-right">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Hari ini</p>
                                <p class="font-bold text-slate-900 text-sm">{{ now()->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @if(isset($petData) && isset($historyRecords) && $historyRecords->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-slate-700 divide-y divide-slate-100">
                                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wide font-semibold">
                                    <tr>
                                        <th class="px-3 py-2.5 text-left">Tanggal</th>
                                        <th class="px-3 py-2.5 text-left">Keluhan</th>
                                        <th class="px-3 py-2.5 text-left">Diagnosa</th>
                                        <th class="px-3 py-2.5 text-left">Tindakan</th>
                                        <th class="px-3 py-2.5 text-left">Resep</th>
                                        <th class="px-3 py-2.5 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
                                    @foreach($historyRecords as $record)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-3 py-2 whitespace-nowrap font-semibold text-slate-900">
                                                <span class="inline-flex px-2 py-0.5 rounded-lg bg-pink-100 text-[#7a0538] font-medium text-xs">
                                                    {{ ($record->tgl_periksa ?? $record->visit_date) ? \Carbon\Carbon::parse($record->tgl_periksa ?? $record->visit_date)->translatedFormat('d M') : '-' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-slate-600">{{ \Illuminate\Support\Str::limit($record->keluhan ?? $record->symptoms ?? '-', 35, '...') }}</td>
                                            <td class="px-3 py-2 text-slate-600">{{ \Illuminate\Support\Str::limit($record->diagnosa ?? $record->diagnosis ?? '-', 35, '...') }}</td>
                                            <td class="px-3 py-2 text-slate-600">
                                                @if($record->treatments && $record->treatments->count())
                                                    {{ $record->treatments->pluck('nama_tindakan')->implode(', ') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-slate-600">
                                                @if(isset($record->prescriptionItems) && count($record->prescriptionItems))
                                                    {{ $record->prescriptionItems->map(fn($item) => $item->medicine->nama_obat . ' (' . $item->jumlah . ')')->implode(', ') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <a href="{{ route('dokter.riwayat.record', $record->id_medical_record) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 text-xs font-semibold border border-blue-200 transition">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-4 sm:px-5 py-8 text-center">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-slate-500 font-medium text-sm">Belum ada riwayat pemeriksaan</p>
                        </div>
                    @endif
                </div>

                {{-- Section 3: Data Pemeriksaan --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-green-600 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Pemeriksaan Hari Ini
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Berat Badan (kg) <span class="text-red-500">*</span></label>
                            <input type="number" name="berat_badan" step="0.1" value="{{ old('berat_badan', isset($draftData) && $draftData ? ($draftData->form_data['berat_badan'] ?? '') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#970747]" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Suhu Tubuh (°C) <span class="text-red-500">*</span></label>
                            <input type="number" name="suhu_tubuh" step="0.1" value="{{ old('suhu_tubuh', isset($draftData) && $draftData ? ($draftData->form_data['suhu_tubuh'] ?? '') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#970747]" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keluhan <span class="text-red-500">*</span></label>
                        <textarea name="keluhan" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#970747]" required>{{ old('keluhan', isset($draftData) && $draftData ? ($draftData->form_data['keluhan'] ?? '') : ($queueData->complaint ?? '')) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosa <span class="text-red-500">*</span></label>
                        <textarea name="diagnosa" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#970747]" placeholder="Masukkan diagnosa medis...">{{ old('diagnosa', isset($draftData) && $draftData ? ($draftData->form_data['diagnosa'] ?? '') : '') }}</textarea>
                    </div>

                    {{-- Tindakan dengan TomSelect --}}
                    <div class="mb-4 space-y-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Tindakan</label>
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <select id="treatmentSelect" name="treatments[]" multiple placeholder="Pilih tindakan..." class="tom-select w-full">
                                    @php
                                        $kategoriLabels = [
                                            'vaksin' => 'Vaksin',
                                            'konsultasi' => 'Konsultasi',
                                            'perawatan' => 'Perawatan',
                                            'operasi' => 'Operasi',
                                            'pemeriksaan_khusus' => 'Pemeriksaan Khusus',
                                            'obat_injeksi' => 'Obat & Injeksi',
                                        ];
                                        $grouped = ($treatments ?? collect())->groupBy(fn($t) => $t->kategori ?? 'lainnya');
                                    @endphp
                                    @foreach($grouped as $kat => $items)
                                        <optgroup label="{{ $kategoriLabels[$kat] ?? ucfirst(str_replace('_', ' ', $kat)) }}">
                                            @foreach($items as $treatment)
                                                <option value="{{ $treatment->id_treatment }}" data-price="{{ (int) $treatment->harga }}" data-kategori="{{ $treatment->kategori }}">
                                                    {{ $treatment->nama_tindakan }} - Rp {{ number_format($treatment->harga, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" id="addTreatmentBtn" class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-gray-300 text-[#970747] hover:bg-pink-50 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Resep Obat --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-blue-600 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        Resep Obat
                    </h2>
                    <div id="prescriptionStockAlert" class="hidden mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>
                    <div id="prescriptionContainer" class="space-y-3"></div>
                    <button type="button" onclick="addPrescription()" class="mt-4 w-full py-2 border-2 border-dashed border-blue-200 rounded-lg text-blue-600 hover:bg-blue-50 transition flex items-center justify-center gap-2 font-bold">
                        + Tambah Obat Pasien
                    </button>
                </div>

                {{-- Section 5: Jadwal Kontrol Berikutnya --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-amber-600 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                        Jadwal Kontrol Berikutnya
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-amber-50 rounded-lg border border-amber-200">
                        <div>
                            <label class="block text-sm font-medium text-amber-800 mb-1 tracking-tight">Tanggal Kontrol Berikutnya</label>
                            <input type="date" name="tgl_kontrol_berikutnya" class="w-full px-4 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-amber-800 mb-1 tracking-tight">Catatan Kontrol</label>
                            <input type="text" name="catatan_kontrol" class="w-full px-4 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500" placeholder="cth: Cek jahitan">
                        </div>
                    </div>
                </div>

                {{-- SECTION RUJUKAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-orange-500 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Surat Rujukan
                        </h2>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="buat_rujukan" value="1" id="buatRujukanCheck" class="sr-only peer">
                            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-orange-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Buat Rujukan</span>
                        </label>
                    </div>
                    <div id="rujukanFields" class="space-y-4 hidden">
                        <input type="text" name="tujuan_rujukan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400" placeholder="Tujuan Rumah Sakit Rujukan">
                        <textarea name="alasan_rujukan" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400" placeholder="Alasan rujukan medis..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Sidebar Ringkasan --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6 space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800 border-b pb-2">Ringkasan Biaya</h2>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Biaya Pemeriksaan (Rp)</label>
                        <input type="number" name="biaya_pemeriksaan" id="biayaPemeriksaan" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#970747]" value="0" oninput="calculateTotal()">
                    </div>
                    <input type="hidden" name="biaya_tindakan" id="biayaTindakan" value="0">
                    <div class="flex justify-between text-sm"><span>Total Tindakan</span> <span id="summaryTindakan" class="font-bold">Rp 0</span></div>
                    <div class="flex justify-between text-sm"><span>Total Obat</span> <span id="summaryObat" class="font-bold">Rp 0</span></div>
                    <hr class="border-dashed">
                    <div class="flex justify-between text-lg font-bold text-[#970747]"><span>Total Akhir</span> <span id="summaryTotal">Rp 0</span></div>
                    
                    <button type="submit" class="w-full bg-[#970747] text-white py-4 rounded-2xl font-bold hover:bg-pink-800 transition shadow-lg active:scale-95">
                        SIMPAN PEMERIKSAAN
                    </button>
                </div>
            </div>
        </div>
    </form>
@push('scripts')
<script>
document.getElementById('examinationForm').addEventListener('submit', function(e) {
    const now = new Date();
    const pad = n => n.toString().padStart(2, '0');
    const formatted = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
    document.getElementById('tgl_periksa_hidden').value = formatted;
});
</script>
@endpush
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script type="application/json" id="medicines-data">{!! json_encode($medicines) !!}</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Inisialisasi TomSelect
    const tSelect = document.getElementById('treatmentSelect');
    let tTomSelect;
    function initTomSelectAndRestoreDraft() {
        tTomSelect = new TomSelect(tSelect, { 
            plugins: ['remove_button', 'optgroup_columns'],
            optgroupField: 'optgroup',
            lockOptgroupOrder: true,
            onChange: function() { 
                console.log('[TomSelect] onChange triggered');
                saveDraft(); 
                calculateTotal(); 
            },
            render: {
                option: function(data, escape) {
                    return `<div class='flex items-center justify-between'>
                        <span>${escape(data.text)}</span>
                        <button type='button' class='delete-tindakan-option ml-2 text-red-500 hover:text-red-700' data-id='${escape(data.value)}' tabindex='-1' style='background:transparent;border:none;outline:none;cursor:pointer;'>
                            <svg class='w-4 h-4 inline' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg>
                        </button>
                    </div>`;
                }
            }
        });
        window.tTomSelect = tTomSelect; // assign global
        console.log('[initTomSelectAndRestoreDraft] TomSelect initialized, now restoring draft');
        restoreDraft();
    }

    // Event delegation untuk hapus di dropdown
    document.body.addEventListener('mousedown', function(e) {
        if (e.target.closest('.delete-tindakan-option')) {
            e.preventDefault();
            e.stopPropagation();
            const btn = e.target.closest('.delete-tindakan-option');
            const id = btn.getAttribute('data-id');
            Swal.fire({
                title: 'Hapus Tindakan?',
                text: 'Tindakan akan dihapus permanen dari database!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#e3342f',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('dokter.api.treatments.destroy') }}", {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id })
                    })
                    .then(res => res.json())
                    .then(r => {
                        if (r.success) {
                            tTomSelect.removeOption(id);
                            tTomSelect.clear();
                            calculateTotal();
                            Swal.fire('Berhasil', 'Tindakan dihapus.', 'success');
                        } else {
                            Swal.fire('Gagal', r.message || 'Gagal menghapus.', 'error');
                        }
                    });
                }
            });
        }
    });

    // 2. SweetAlert Tambah Tindakan
    const addBtn = document.getElementById('addTreatmentBtn');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
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
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ nama_tindakan: n, harga: p, kategori: k || null })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Cek kembali koneksi database Anda.');
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Gagal Simpan: ${error}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value.success) {
                    const data = result.value.treatment;
                    tTomSelect.addOption({ 
                        value: data.id, 
                        text: data.nama_tindakan + ' - Rp ' + parseInt(data.harga).toLocaleString('id-ID') 
                    });
                    tTomSelect.addItem(data.id);
                    calculateTotal();
                    Swal.fire('Berhasil!', 'Tindakan tersimpan permanen.', 'success');
                }
            });
        });
    }

    // 3. Toggle Rujukan
    const rCheck = document.getElementById('buatRujukanCheck');
    const rBox = document.getElementById('rujukanFields');
    if (rCheck) {
        rCheck.addEventListener('change', function() { 
            rBox.classList.toggle('hidden', !this.checked); 
        });
    }

    // 4. Logic Resep Obat - GRID SYSTEM TERBARU
    const meds = JSON.parse(document.getElementById('medicines-data').textContent);
    let pIdx = 0;
    window.addPrescription = function(data) {
        console.log('[addPrescription] called with:', data);
        const idx = typeof data === 'object' && data !== null && data.idx !== undefined ? data.idx : pIdx;
        const val = typeof data === 'object' && data !== null ? data : {};
        const row = document.createElement('div');
        row.className = 'prescription-row grid grid-cols-1 md:grid-cols-12 gap-4 bg-pink-50/50 p-4 rounded-xl border border-pink-100 items-end mb-3';
        row.innerHTML = `
            <div class="md:col-span-5">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Obat</label>
                <select name="prescriptions[${idx}][id_medicine]" class="medicine-select w-full rounded-lg text-sm border-gray-200 focus:ring-[#970747] focus:border-[#970747]" required>
                    <option value="">-- Pilih Obat --</option>
                    ${meds.map(m => `<option value="${m.id_medicine}" data-harga="${m.harga_jual}"${val.id_medicine == m.id_medicine ? ' selected' : ''}>${m.nama_obat} (Stok:${m.stok})</option>`).join('')}
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah</label>
                <input type="number" name="prescriptions[${idx}][jumlah]" value="${val.jumlah || 1}" min="1" class="w-full rounded-lg text-sm border-gray-200 text-center focus:ring-[#970747] focus:border-[#970747]" required>
            </div>
            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Aturan Minum / Instruksi</label>
                <input type="text" name="prescriptions[${idx}][instruksi]" class="w-full rounded-lg text-sm border-gray-200 focus:ring-[#970747] focus:border-[#970747]" placeholder="cth: 3x1 hari, setelah makan" value="${val.instruksi || ''}" required>
            </div>
            <div class="md:col-span-1 flex justify-center">
                <button type="button" onclick="this.parentElement.parentElement.remove(); calculateTotal(); saveDraft();" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition duration-200 mb-1" title="Hapus obat">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        `;
        document.getElementById('prescriptionContainer').appendChild(row);
        rebindPrescriptionEvents();
        saveDraft();
        validatePrescriptionStocks();
        pIdx = Math.max(pIdx, idx + 1);
    };

    // 5. Kalkulasi Total Biaya Otomatis
    window.calculateTotal = function() {
        let tndTotal = 0;
        tTomSelect.getValue().forEach(v => {
            const o = tSelect.querySelector(`option[value="${v}"]`);
            if (o) tndTotal += parseInt(o.dataset.price || 0);
        });

        let obtTotal = 0;
        document.querySelectorAll('.prescription-row').forEach(r => {
            const s = r.querySelector('.medicine-select');
            const q = parseInt(r.querySelector('input[type="number"]').value) || 0;
            const o = s.options[s.selectedIndex];
            if (o && o.dataset.harga) obtTotal += parseInt(o.dataset.harga) * q;
        });

        const prm = parseInt(document.getElementById('biayaPemeriksaan').value) || 0;
        document.getElementById('biayaTindakan').value = tndTotal;
        document.getElementById('summaryTindakan').textContent = 'Rp ' + tndTotal.toLocaleString('id-ID');
        document.getElementById('summaryObat').textContent = 'Rp ' + obtTotal.toLocaleString('id-ID');
        document.getElementById('summaryTotal').textContent = 'Rp ' + (prm + tndTotal + obtTotal).toLocaleString('id-ID');
    };

    window.validatePrescriptionStocks = function() {
        const alertBox = document.getElementById('prescriptionStockAlert');
        const submitBtn = form.querySelector('button[type="submit"]');
        const rows = Array.from(document.querySelectorAll('.prescription-row'));
        const totals = new Map();
        const medicineMeta = new Map(meds.map(m => [String(m.id_medicine), m]));
        const issues = [];

        rows.forEach((row) => {
            const select = row.querySelector('.medicine-select');
            const qtyInput = row.querySelector('input[type="number"]');
            const medicineId = select?.value || '';
            const qty = parseInt(qtyInput?.value || '0', 10) || 0;

            if (!medicineId || qty < 1) {
                return;
            }

            totals.set(medicineId, (totals.get(medicineId) || 0) + qty);

            const medicine = medicineMeta.get(String(medicineId));
            if (!medicine) {
                return;
            }

            if (qty > parseInt(medicine.stok || 0, 10)) {
                issues.push(`Jumlah ${medicine.nama_obat} melebihi stok tersedia (${medicine.stok}).`);
            }
        });

        totals.forEach((qty, medicineId) => {
            const medicine = medicineMeta.get(String(medicineId));
            if (!medicine) return;
            if (qty > parseInt(medicine.stok || 0, 10)) {
                issues.push(`Total resep ${medicine.nama_obat} adalah ${qty}, sedangkan stok hanya ${medicine.stok}.`);
            }
        });

        if (issues.length > 0) {
            alertBox.textContent = issues[0];
            alertBox.classList.remove('hidden');
            if (submitBtn) submitBtn.disabled = true;
            return false;
        }

        alertBox.classList.add('hidden');
        alertBox.textContent = '';
        if (submitBtn) submitBtn.disabled = false;
        return true;
    };

    // --- AUTOSAVE DRAFT PEMERIKSAAN (FINAL, FULL SYNC) ---
    const form = document.getElementById('examinationForm');
    const draftKey = 'draft-examination-{{ isset($queueData) ? $queueData->id_queue : (isset($petData) ? $petData->id_pet : 'new') }}';

    function rebindPrescriptionEvents() {
        const container = document.getElementById('prescriptionContainer');
        container.querySelectorAll('input, select').forEach(function(el) {
            el.oninput = function() { saveDraft(); calculateTotal(); validatePrescriptionStocks(); };
            el.onchange = function() { saveDraft(); calculateTotal(); validatePrescriptionStocks(); };
        });
    }

    function observePrescriptionContainer() {
        const container = document.getElementById('prescriptionContainer');
        const observer = new MutationObserver(() => {
            rebindPrescriptionEvents();
            saveDraft();
            calculateTotal();
        });
        observer.observe(container, { childList: true, subtree: true });
    }

    function restoreDraft() {
        console.log('[restoreDraft] called');
        if (localStorage.getItem(draftKey)) {
            try {
                const raw = localStorage.getItem(draftKey);
                console.log('[restoreDraft] raw localStorage:', raw);
                const draft = JSON.parse(raw);
                console.log('[restoreDraft] loaded draft:', draft);
                // Restore field standar
                Object.keys(draft).forEach(function(name) {
                    if (name === '_prescriptions' || name === '_treatments' || name === '_biaya') return;
                    if (name === '_token' || name === '_method') return;
                    const el = form.elements[name];
                    if (!el) return;
                    if (el.type === 'checkbox' || el.type === 'radio') {
                        el.checked = draft[name];
                    } else if (el.type === 'select-multiple') {
                        Array.from(el.options).forEach(opt => opt.selected = (draft[name]||[]).includes(opt.value));
                    } else {
                        el.value = draft[name];
                    }
                });
                // Restore prescription (obat) dinamis dari array data
                if (Array.isArray(draft._prescriptions)) {
                    document.getElementById('prescriptionContainer').innerHTML = '';
                    draft._prescriptions.forEach(function(p) {
                        console.log('[restoreDraft] restoring prescription:', p);
                        window.addPrescription(p);
                    });
                }
                // Restore tindakan (TomSelect) setelah TomSelect benar-benar siap
                var tindakanDraft = draft._treatments || draft["tindakan_ids[]"];
                console.log('[restoreDraft] tindakanDraft:', tindakanDraft, 'typeof:', typeof tindakanDraft, 'isArray:', Array.isArray(tindakanDraft));
                if (Array.isArray(tindakanDraft) && tindakanDraft.length > 0) {
                    let restoreTindakan = function() {
                        if (window.tTomSelect && typeof tTomSelect.setValue === 'function') {
                            console.log('[restoreDraft] tTomSelect is ready, will setValue:', tindakanDraft);
                            tindakanDraft.forEach(function(val) {
                                if (!tSelect.querySelector(`option[value="${val}"]`)) {
                                    tTomSelect.addOption({value: val, text: val});
                                }
                            });
                            tTomSelect.setValue(tindakanDraft);
                            console.log('[restoreDraft] tTomSelect.getValue after setValue:', tTomSelect.getValue());
                            tTomSelect.refreshOptions(false);
                            tTomSelect.updateOriginalInput();
                            tSelect.dispatchEvent(new Event('input', { bubbles: true }));
                            tSelect.dispatchEvent(new Event('change', { bubbles: true }));
                            saveDraft();
                            if (typeof calculateTotal === 'function') calculateTotal();
                        } else {
                            console.log('[restoreDraft] tTomSelect not ready, retrying...');
                            setTimeout(restoreTindakan, 100);
                        }
                    };
                    restoreTindakan();
                } else {
                    console.log('[restoreDraft] tindakanDraft is not array or empty:', tindakanDraft);
                }
                // Restore ringkasan biaya
                if (draft._biaya) {
                    Object.keys(draft._biaya).forEach(function(id) {
                        const el = document.getElementById(id);
                        if (el) el.value = draft._biaya[id];
                    });
                }
                // Trigger kalkulasi ulang setelah restore
                setTimeout(function() {
                    if (typeof calculateTotal === 'function') calculateTotal();
                    if (typeof validatePrescriptionStocks === 'function') validatePrescriptionStocks();
                }, 400);
            } catch(e) { console.error('[restoreDraft] error:', e); }
        }
    }

    function saveDraft() {
        const data = {};
        Array.from(form.elements).forEach(function(el) {
            if (!el.name) return;
            if (el.name === '_token' || el.name === '_method') return;
            if (el.type === 'checkbox' || el.type === 'radio') {
                data[el.name] = el.checked;
            } else if (el.type === 'select-multiple') {
                data[el.name] = Array.from(el.selectedOptions).map(opt => opt.value);
            } else {
                data[el.name] = el.value;
            }
        });
        // Simpan prescription (obat) dinamis sebagai array data
        data._prescriptions = Array.from(document.querySelectorAll('.prescription-row')).map(function(row, idx) {
            return {
                idx: idx,
                id_medicine: row.querySelector('.medicine-select')?.value || '',
                jumlah: row.querySelector('input[type="number"]')?.value || 1,
                instruksi: row.querySelector('input[type="text"]')?.value || ''
            };
        });
        // Simpan tindakan (TomSelect)
        if (window.tTomSelect) {
            data._treatments = tTomSelect.getValue();
            data["tindakan_ids[]"] = tTomSelect.getValue();
            console.log('[saveDraft] tTomSelect.getValue:', tTomSelect.getValue());
        }
        // Simpan ringkasan biaya
        data._biaya = {
            biayaPemeriksaan: document.getElementById('biayaPemeriksaan').value,
            biayaTindakan: document.getElementById('biayaTindakan').value
        };
        console.log('[saveDraft] data:', data);
        localStorage.setItem(draftKey, JSON.stringify(data));
    }

    // Restore draft & event binding setelah TomSelect siap
    initTomSelectAndRestoreDraft();
    form.addEventListener('input', saveDraft);
    document.getElementById('prescriptionContainer').addEventListener('input', saveDraft);
    document.getElementById('prescriptionContainer').addEventListener('change', saveDraft);
    document.getElementById('biayaPemeriksaan').addEventListener('input', saveDraft);
    form.addEventListener('submit', function(e) {
        if (!validatePrescriptionStocks()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Stok obat tidak cukup',
                text: 'Periksa kembali jumlah resep karena ada obat yang melebihi stok tersedia.',
                confirmButtonColor: '#970747'
            });
            return;
        }
    });
    // Jaga-jaga: trigger saveDraft setiap 2 detik jika ada perubahan JS dinamis
    setInterval(saveDraft, 2000);
    // Hapus draft jika submit sukses
    form.addEventListener('submit', function() {
        if (validatePrescriptionStocks()) {
            localStorage.removeItem(draftKey);
        }
    });
    observePrescriptionContainer();
});
</script>
@endpush
@endsection