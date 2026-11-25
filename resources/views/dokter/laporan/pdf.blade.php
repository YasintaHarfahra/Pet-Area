<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekam Medis - Pet Area</title>
    <style>
        @page {
            margin: 2cm 1.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.5;
            margin: 0;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16pt;
            margin: 0;
            letter-spacing: 1px;
        }
        .header h2 {
            font-size: 12pt;
            font-weight: normal;
            margin: 4px 0 0 0;
            color: #555;
        }

        /* Info section */
        .info-table {
            width: 100%;
            margin-bottom: 24px;
            font-size: 9.5pt;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            width: 140px;
            color: #555;
        }

        /* Section title */
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 4px;
            border-bottom: 1px solid #999;
        }

        /* Summary stats inline */
        .stats-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .stats-row td {
            width: 25%;
            text-align: center;
            padding: 10px 8px;
            border: 1px solid #ddd;
        }
        .stats-number {
            font-size: 18pt;
            font-weight: bold;
            color: #333;
        }
        .stats-label {
            font-size: 8pt;
            color: #777;
            text-transform: uppercase;
        }

        /* Simple tables */
        .simple-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 9pt;
        }
        .simple-table th {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 6px 10px;
            text-align: left;
            font-size: 8.5pt;
            text-transform: uppercase;
            color: #555;
        }
        .simple-table td {
            border: 1px solid #ddd;
            padding: 5px 10px;
        }
        .simple-table .total-row {
            font-weight: bold;
            background: #f9f9f9;
        }

        /* Detail table */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }
        .detail-table th {
            background: #333;
            color: #fff;
            padding: 7px 6px;
            text-align: left;
            font-size: 8pt;
            text-transform: uppercase;
        }
        .detail-table td {
            border-bottom: 1px solid #ddd;
            padding: 5px 6px;
            vertical-align: top;
        }
        .detail-table tr:nth-child(even) {
            background: #fafafa;
        }

        /* Page break */
        .page-break {
            page-break-before: always;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: -1cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #999;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .no-data { text-align: center; color: #999; font-style: italic; padding: 10px; }
    </style>
</head>
<body>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- HALAMAN 1 - RINGKASAN                      --}}
    {{-- ═══════════════════════════════════════════ --}}

    <div class="header">
        <h1>PET AREA</h1>
        <h2>Laporan Rekam Medis</h2>
    </div>

    {{-- Info Laporan --}}
    <table class="info-table">
        <tr>
            <td class="info-label">Periode</td>
            <td>:
                @if(isset($start_date) && isset($end_date) && $start_date && $end_date)
                    {{ date('d/m/Y', strtotime($start_date)) }} s/d {{ date('d/m/Y', strtotime($end_date)) }}
                @elseif(isset($start_date) && $start_date)
                    {{ date('d/m/Y', strtotime($start_date)) }} s/d Sekarang
                @elseif(isset($end_date) && $end_date)
                    Awal s/d {{ date('d/m/Y', strtotime($end_date)) }}
                @else
                    Semua Periode
                @endif
            </td>
        </tr>
        <tr>
            <td class="info-label">Dicetak Oleh</td>
            <td>: {{ $doctor_name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Tanggal Cetak</td>
            <td>: {{ $generated_at ?? now()->format('d/m/Y H:i') }} WIB</td>
        </tr>
        @if(!empty($filter_diagnosis) || !empty($filter_pet_name))
        <tr>
            <td class="info-label">Filter</td>
            <td>:
                {{ !empty($filter_diagnosis) ? 'Diagnosa: '.$filter_diagnosis : '' }}
                {{ !empty($filter_pet_name) ? (!empty($filter_diagnosis) ? ', ' : '') . 'Hewan: '.$filter_pet_name : '' }}
            </td>
        </tr>
        @endif
    </table>

    {{-- Statistik Ringkasan --}}
    <div class="section-title">Ringkasan</div>

    <table class="stats-row">
        <tr>
            <td>
                <div class="stats-number">{{ $totalPasien ?? 0 }}</div>
                <div class="stats-label">Total Pasien</div>
            </td>
            <td>
                <div class="stats-number">{{ $totalRekamMedis ?? 0 }}</div>
                <div class="stats-label">Total Rekam Medis</div>
            </td>
            <td>
                <div class="stats-number">{{ isset($spesiesSummary) ? $spesiesSummary->count() : 0 }}</div>
                <div class="stats-label">Jenis Spesies</div>
            </td>
            <td>
                <div class="stats-number">{{ isset($diagnosaSummary) ? $diagnosaSummary->count() : 0 }}</div>
                <div class="stats-label">Jenis Diagnosa</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Diagnosa --}}
    <div class="section-title">Rangkuman Diagnosa</div>

    <table class="simple-table">
        <thead>
            <tr>
                <th width="8%">No</th>
                <th>Diagnosa</th>
                <th width="15%" class="text-center">Jumlah</th>
                <th width="15%" class="text-center">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php $rank = 0; @endphp
            @forelse($diagnosaSummary ?? collect() as $diagnosa => $jumlah)
                @php $rank++; @endphp
                <tr>
                    <td class="text-center">{{ $rank }}</td>
                    <td>{{ ucfirst($diagnosa) }}</td>
                    <td class="text-center">{{ $jumlah }}</td>
                    <td class="text-center">{{ ($totalRekamMedis ?? 0) > 0 ? round(($jumlah / $totalRekamMedis) * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr><td colspan="4" class="no-data">Tidak ada data diagnosa</td></tr>
            @endforelse
            @if(isset($diagnosaSummary) && $diagnosaSummary->count() > 0)
            <tr class="total-row">
                <td colspan="2" class="text-right">Total</td>
                <td class="text-center">{{ $diagnosaSummary->sum() }}</td>
                <td class="text-center">100%</td>
            </tr>
            @endif
        </tbody>
    </table>

    {{-- Tabel Spesies --}}
    <div class="section-title">Rangkuman Spesies</div>

    <table class="simple-table">
        <thead>
            <tr>
                <th width="8%">No</th>
                <th>Jenis Hewan</th>
                <th width="15%" class="text-center">Jumlah Pasien</th>
                <th width="15%" class="text-center">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php $sRank = 0; @endphp
            @forelse($spesiesSummary ?? collect() as $spesies => $jumlah)
                @php $sRank++; @endphp
                <tr>
                    <td class="text-center">{{ $sRank }}</td>
                    <td>{{ $spesies }}</td>
                    <td class="text-center">{{ $jumlah }}</td>
                    <td class="text-center">{{ ($totalPasien ?? 0) > 0 ? round(($jumlah / $totalPasien) * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr><td colspan="4" class="no-data">Tidak ada data spesies</td></tr>
            @endforelse
            @if(isset($spesiesSummary) && $spesiesSummary->count() > 0)
            <tr class="total-row">
                <td colspan="2" class="text-right">Total</td>
                <td class="text-center">{{ $spesiesSummary->sum() }}</td>
                <td class="text-center">100%</td>
            </tr>
            @endif
        </tbody>
    </table>

    @if(!empty($diagnosaTerbanyak))
    <p style="margin-top: 10px; font-size: 9.5pt;">
        <strong>Kasus Terbanyak:</strong> {{ ucfirst($diagnosaTerbanyak) }}
        ({{ $jumlahDiagnosaTerbanyak ?? 0 }} dari {{ $totalRekamMedis ?? 0 }} kasus / {{ ($totalRekamMedis ?? 0) > 0 ? round((($jumlahDiagnosaTerbanyak ?? 0) / $totalRekamMedis) * 100, 1) : 0 }}%)
    </p>
    @endif

    {{-- ═══════════════════════════════════════════ --}}
    {{-- HALAMAN 2 - DETAIL REKAM MEDIS             --}}
    {{-- ═══════════════════════════════════════════ --}}

    <div class="page-break"></div>

    <div class="header">
        <h1>PET AREA</h1>
        <h2>Detail Rekam Medis</h2>
    </div>

    <table class="detail-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Tanggal</th>
                <th width="14%">Pasien</th>
                <th width="12%">Pemilik</th>
                <th width="14%">Diagnosa</th>
                <th width="20%">Tindakan</th>
                <th width="26%">Resep Obat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $record)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    @php
                        $dateObj = $record->visit_date ?? $record->tgl_periksa ?? $record->created_at;
                    @endphp
                    {{ $dateObj ? (is_string($dateObj) ? date('d/m/Y', strtotime($dateObj)) : $dateObj->format('d/m/Y')) : '-' }}
                    <br>
                    <span style="font-size:7.5pt; color:#888;">{{ $dateObj ? (is_string($dateObj) ? date('H:i', strtotime($dateObj)) : $dateObj->format('H:i')) : '' }} WIB</span>
                </td>
                <td>
                    <strong>{{ $record->pet->nama_hewan ?? '-' }}</strong>
                    <br>
                    <span style="font-size:7.5pt; color:#666;">{{ $record->pet->jenis ?? '-' }} ({{ $record->pet->jenis_kelamin ?? '-' }})</span>
                </td>
                <td>{{ $record->pet->owner->nama ?? '-' }}</td>
                <td>
                    {{ $record->diagnosis ?? $record->diagnosa ?? '-' }}
                    @if(!empty($record->berat_badan) || !empty($record->suhu_tubuh))
                    <br>
                    <span style="font-size:7.5pt; color:#888;">
                        @if(!empty($record->berat_badan))BB: {{ $record->berat_badan }}kg @endif
                        @if(!empty($record->suhu_tubuh))| S: {{ $record->suhu_tubuh }}&deg;C @endif
                    </span>
                    @endif
                </td>
                <td>
                    @if($record->treatments && $record->treatments->count())
                        @foreach($record->treatments as $t)
                            &bull; {{ $t->nama_tindakan }}<br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($record->prescriptionItems && $record->prescriptionItems->count())
                        @foreach($record->prescriptionItems as $item)
                            &bull; {{ $item->medicine->nama_obat ?? '-' }} x{{ $item->jumlah ?? $item->qty ?? 0 }}
                            @if(!empty($item->instruksi) || !empty($item->instructions))
                                <em style="color:#555;">({{ $item->instruksi ?? $item->instructions }})</em>
                            @endif
                            <br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="no-data">Tidak ada data rekam medis untuk periode ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        Pet Area &mdash; Dicetak pada {{ $generated_at ?? now()->format('d/m/Y H:i') }} WIB
    </div>

</body>
</html>
