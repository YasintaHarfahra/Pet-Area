@extends('layouts.dokter_app')

@section('title', 'Detail Rekam Medis')

@section('content')

<div class="p-4 md:p-6 max-w-5xl mx-auto space-y-5">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ url()->previous() }}" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-lg font-bold text-gray-800 leading-tight">Detail Rekam Medis</h1>
            <p class="text-xs text-gray-400 mt-0.5">{{ $record->visit_date ? $record->visit_date->format('d F Y, H:i') : '-' }} WIB</p>
        </div>
    </div>

    {{-- Pasien Card --}}
    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="flex items-center gap-4 px-5 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-gray-100">
            @if($record->pet?->foto_hewan_url)
            <img src="{{ $record->pet->foto_hewan_url }}" alt="Foto {{ $record->pet?->nama_hewan ?? 'Hewan' }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 flex-shrink-0">
            @else
            <div class="w-12 h-12 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-lg font-bold flex-shrink-0">
                {{ strtoupper(substr($record->pet?->nama_hewan ?? 'P', 0, 1)) }}
            </div>
            @endif
            <div class="min-w-0">
                <h2 class="font-bold text-gray-800 text-base truncate">
                    {{ $record->pet?->nama_hewan ?? '-' }}
                    @if($record->pet?->trashed())
                        <span class="text-[10px] font-medium text-red-400 ml-1">(Dihapus)</span>
                    @endif
                </h2>
                <p class="text-sm text-gray-400">{{ $record->pet?->jenis ?? '-' }} {{ $record->pet?->ras ? '· ' . $record->pet->ras : '' }}</p>
            </div>
        </div>
        <div class="px-5 py-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-x-6 gap-y-3.5 text-sm">
            <div>
                <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">Jenis Kelamin</p>
                <p class="text-gray-700 mt-0.5 capitalize">{{ $record->pet?->jenis_kelamin ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">No. Rekam Medis</p>
                <p class="text-gray-700 mt-0.5 font-mono text-xs">{{ $record->pet?->no_rm ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">Pemilik</p>
                <p class="text-gray-700 mt-0.5">{{ $record->pet?->owner?->nama ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">Dokter Pemeriksa</p>
                <p class="text-gray-700 mt-0.5">Dr. {{ $record->doctor->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">Berat Badan</p>
                <p class="text-gray-700 mt-0.5">{{ $record->berat_badan ? $record->berat_badan . ' kg' : '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">Suhu Tubuh</p>
                <p class="text-gray-700 mt-0.5">{{ $record->suhu_tubuh ? $record->suhu_tubuh . ' °C' : '-' }}</p>
            </div>
            @if($record->next_control_date)
            <div>
                <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">Kontrol Ulang</p>
                <p class="text-gray-700 mt-0.5">{{ \Carbon\Carbon::parse($record->next_control_date)->format('d M Y') }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Diagnosis & Keluhan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                <h3 class="text-sm font-semibold text-gray-700">Diagnosis</h3>
            </div>
            <div class="px-5 py-4">
                <p class="text-sm text-gray-600 leading-relaxed">{{ $record->diagnosis ?? '-' }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                <h3 class="text-sm font-semibold text-gray-700">Gejala / Keluhan</h3>
            </div>
            <div class="px-5 py-4">
                <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $record->symptoms ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Pemeriksaan Fisik --}}
    @if($record->physical_examination)
    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
            <h3 class="text-sm font-semibold text-gray-700">Pemeriksaan Fisik</h3>
        </div>
        <div class="px-5 py-4">
            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $record->physical_examination }}</p>
        </div>
    </div>
    @endif

    {{-- Tindakan --}}
    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            <h3 class="text-sm font-semibold text-gray-700">Tindakan</h3>
        </div>
        <div class="px-5 py-3">
            @if($record->treatments && $record->treatments->count())
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-2 pr-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Nama</th>
                        <th class="text-left py-2 pr-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Kategori</th>
                        <th class="text-right py-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->treatments as $tindakan)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="py-2.5 pr-3 text-gray-700">{{ $tindakan->nama_tindakan }}</td>
                        <td class="py-2.5 pr-3 text-gray-400">{{ $tindakan->kategori ?? '-' }}</td>
                        <td class="py-2.5 text-right text-gray-600 tabular-nums">{{ \App\Helpers\CurrencyHelper::formatRupiah($tindakan->pivot->harga ?? $tindakan->harga ?? 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-gray-400 text-sm py-2">Tidak ada tindakan</p>
            @endif
        </div>
    </div>

    {{-- Resep Obat --}}
    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>
            <h3 class="text-sm font-semibold text-gray-700">Resep Obat</h3>
        </div>
        <div class="px-5 py-3">
            @if($record->prescriptionItems && $record->prescriptionItems->count())
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-2 pr-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Obat</th>
                        <th class="text-center py-2 px-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Qty</th>
                        <th class="text-left py-2 px-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Aturan Pakai</th>
                        <th class="text-right py-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->prescriptionItems as $item)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="py-2.5 pr-3 text-gray-700">{{ $item->medicine->nama_obat ?? '-' }} <span class="text-gray-400 text-xs">({{ $item->medicine->satuan ?? '-' }})</span></td>
                        <td class="py-2.5 px-2 text-center text-gray-600 tabular-nums">{{ $item->jumlah ?? $item->qty ?? '-' }}</td>
                        <td class="py-2.5 px-2 text-gray-400">{{ $item->instruksi ?? $item->instructions ?? '-' }}</td>
                        <td class="py-2.5 text-right text-gray-600 tabular-nums">{{ \App\Helpers\CurrencyHelper::formatRupiah($item->subtotal ?? 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-gray-400 text-sm py-2">Tidak ada resep obat</p>
            @endif
        </div>
    </div>

    {{-- Rujukan & Kontrol --}}
    @if($record->referral || ($record->next_control_date && $record->follow_up_notes))
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @if($record->referral)
        <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span>
                <h3 class="text-sm font-semibold text-gray-700">Surat Rujukan</h3>
            </div>
            <div class="px-5 py-4 space-y-2 text-sm">
                <div>
                    <span class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">Tujuan</span>
                    <p class="text-gray-700 mt-0.5">{{ $record->referral->tujuan_rujukan }}</p>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">Alasan</span>
                    <p class="text-gray-700 mt-0.5">{{ $record->referral->alasan_rujukan }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($record->next_control_date && $record->follow_up_notes)
        <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span>
                <h3 class="text-sm font-semibold text-gray-700">Kontrol Berikutnya</h3>
            </div>
            <div class="px-5 py-4 space-y-2 text-sm">
                <div>
                    <span class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">Tanggal</span>
                    <p class="text-gray-700 mt-0.5 font-medium">{{ \Carbon\Carbon::parse($record->next_control_date)->format('d F Y') }}</p>
                </div>
                @if($record->follow_up_notes)
                <div>
                    <span class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">Catatan</span>
                    <p class="text-gray-700 mt-0.5">{{ $record->follow_up_notes }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- Catatan Tambahan --}}
    @if($record->notes)
    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
            <h3 class="text-sm font-semibold text-gray-700">Catatan Tambahan</h3>
        </div>
        <div class="px-5 py-4">
            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $record->notes }}</p>
        </div>
    </div>
    @endif

    {{-- Rincian Biaya --}}
    @if($record->total_biaya || $record->biaya_pemeriksaan || $record->biaya_tindakan || $record->biaya_obat)
    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
            <h3 class="text-sm font-semibold text-gray-700">Rincian Biaya</h3>
        </div>
        <div class="px-5 py-4">
            <div class="text-sm max-w-xs ml-auto space-y-2">
                @if($record->biaya_pemeriksaan)
                <div class="flex justify-between">
                    <span class="text-gray-400">Pemeriksaan</span>
                    <span class="text-gray-600 tabular-nums">{{ \App\Helpers\CurrencyHelper::formatRupiah($record->biaya_pemeriksaan) }}</span>
                </div>
                @endif
                @if($record->biaya_tindakan)
                <div class="flex justify-between">
                    <span class="text-gray-400">Tindakan</span>
                    <span class="text-gray-600 tabular-nums">{{ \App\Helpers\CurrencyHelper::formatRupiah($record->biaya_tindakan) }}</span>
                </div>
                @endif
                @if($record->biaya_obat)
                <div class="flex justify-between">
                    <span class="text-gray-400">Obat</span>
                    <span class="text-gray-600 tabular-nums">{{ \App\Helpers\CurrencyHelper::formatRupiah($record->biaya_obat) }}</span>
                </div>
                @endif
                <div class="border-t border-gray-100 pt-2 flex justify-between">
                    <span class="font-bold text-gray-800">Total</span>
                    <span class="font-bold text-gray-800 tabular-nums">{{ \App\Helpers\CurrencyHelper::formatRupiah($record->total_biaya ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
