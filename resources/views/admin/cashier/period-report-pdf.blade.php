<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Periode - Pet Area</title>
    <style>
        @page { margin: 1.2cm 1cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9pt; color: #2d2d2d; line-height: 1.45; margin: 0; }

        /* ═══ HEADER ═══ */
        .header {
            background: #970747;
            color: white;
            padding: 18px 24px;
            margin: -1.2cm -1cm 0 -1cm;
            width: 100%;
        }
        .header-table { width: 100%; }
        .header-table td { vertical-align: middle; }
        .clinic-name { font-size: 18pt; font-weight: bold; letter-spacing: 1px; margin: 0; }
        .clinic-sub { font-size: 9pt; opacity: 0.85; margin-top: 2px; }
        .report-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 8pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .report-date {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 4px;
        }

        /* ═══ META INFO ═══ */
        .meta-section {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 12px 16px;
            margin: 16px 0;
            font-size: 8.5pt;
        }
        .meta-table { width: 100%; }
        .meta-table td { padding-right: 8px; vertical-align: top; }
        .meta-label { color: #868e96; font-size: 7pt; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold; margin-bottom: 2px; }
        .meta-value { color: #2d2d2d; font-weight: 600; }

        /* ═══ STAT CARDS ═══ */
        .stats-table { width: 100%; margin-bottom: 16px; border-spacing: 8px 0; }
        .stat-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 14px;
            vertical-align: top;
        }
        .stat-card-primary { border-left: 4px solid #970747; }
        .stat-card-green { border-left: 4px solid #16a34a; }
        .stat-card-blue { border-left: 4px solid #2563eb; }
        .stat-card-purple { border-left: 4px solid #7c3aed; }
        .stat-card-orange { border-left: 4px solid #ea580c; }
        .stat-number { font-size: 18pt; font-weight: bold; color: #1a1a1a; line-height: 1.1; }
        .stat-number-sm { font-size: 13pt; font-weight: bold; color: #1a1a1a; line-height: 1.1; }
        .stat-label { font-size: 7pt; color: #868e96; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold; }
        .stat-sub { font-size: 8pt; color: #adb5bd; font-weight: normal; }

        /* ═══ SECTION HEADERS ═══ */
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #970747;
            padding-bottom: 6px;
            border-bottom: 2px solid #970747;
            margin: 20px 0 10px 0;
        }

        /* ═══ PAYMENT METHOD SUMMARY ═══ */
        .method-table { width: 100%; border-collapse: collapse; font-size: 9pt; margin-bottom: 16px; }
        .method-table th {
            background: #f1f3f5;
            color: #495057;
            padding: 8px 12px;
            text-align: left;
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #dee2e6;
        }
        .method-table td { padding: 8px 12px; border-bottom: 1px solid #f1f3f5; }
        .method-table .total-row { background: #f8f9fa; font-weight: bold; }
        .method-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: bold;
        }
        .badge-tunai { background: #dcfce7; color: #166534; }
        .badge-transfer { background: #fdf2f7; color: #970747; }
        .badge-qris { background: #ede9fe; color: #6d28d9; }
        .bar-bg { background: #f1f3f5; border-radius: 3px; height: 8px; width: 100%; }
        .bar-fill-green { background: #16a34a; border-radius: 3px; height: 8px; }
        .bar-fill-pink { background: #970747; border-radius: 3px; height: 8px; }
        .bar-fill-purple { background: #7c3aed; border-radius: 3px; height: 8px; }

        /* ═══ MAIN DATA TABLE ═══ */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .data-table th {
            background: #970747;
            color: white;
            padding: 8px 8px;
            text-align: left;
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table td {
            padding: 7px 8px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
            font-size: 8pt;
        }
        .data-table .even-row { background-color: #fafbfc; }
        .invoice-text { font-weight: bold; color: #970747; font-size: 8.5pt; }
        .time-text { font-size: 7pt; color: #868e96; }
        .pet-name { font-weight: bold; color: #1a1a1a; }
        .species-tag {
            display: inline-block;
            background: #fdf2f7;
            color: #970747;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
        }
        .amount-main { font-weight: bold; color: #1a1a1a; font-size: 9pt; }
        .amount-detail { font-size: 7pt; color: #868e96; }

        /* ═══ FOOTER ═══ */
        .footer {
            position: fixed;
            bottom: 0;
            left: -1cm;
            right: -1cm;
            background: #f8f9fa;
            font-size: 7pt;
            color: #868e96;
            text-align: center;
            padding: 6px 0;
            border-top: 1px solid #e9ecef;
        }
        .no-data { text-align: center; color: #adb5bd; font-style: italic; padding: 16px 0; }

        /* ═══ SIGNATURE ═══ */
        .signature-section { margin-top: 40px; width: 100%; }
        .signature-section td { vertical-align: top; text-align: center; padding: 0 20px; }
        .signature-label { font-size: 8pt; color: #868e96; margin-bottom: 60px; }
        .signature-line { border-top: 1px solid #2d2d2d; padding-top: 4px; font-weight: bold; font-size: 9pt; }
    </style>
</head>
<body>

    {{-- ═══ HEADER BANNER ═══ --}}
    <div class="header">
        <table class="header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="clinic-name">PET AREA</div>
                    <div class="clinic-sub">Klinik Hewan &mdash; Laporan Transaksi Periode</div>
                </td>
                <td style="text-align: right;">
                    <span class="report-badge">LAPORAN KASIR</span>
                    <div class="report-date">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ═══ META INFO ═══ --}}
    <div class="meta-section">
        <table class="meta-table" cellpadding="0" cellspacing="0">
            <tr>
                <td width="25%">
                    <div class="meta-label">Periode Laporan</div>
                    <div class="meta-value">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
                </td>
                <td width="25%">
                    <div class="meta-label">Dicetak Oleh</div>
                    <div class="meta-value">{{ $admin_name }}</div>
                </td>
                <td width="25%">
                    <div class="meta-label">Waktu Cetak</div>
                    <div class="meta-value">{{ $generated_at }} WIB</div>
                </td>
                <td width="25%">
                    <div class="meta-label">Total Transaksi</div>
                    <div class="meta-value">{{ $summary['total_transaksi'] }} transaksi lunas</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ═══ STAT CARDS ═══ --}}
    <table class="stats-table" cellspacing="8">
        <tr>
            <td class="stat-card stat-card-primary" width="40%">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-number">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</div>
                <div class="stat-sub">dari {{ $summary['total_transaksi'] }} transaksi</div>
            </td>
            <td class="stat-card stat-card-green" width="20%">
                <div class="stat-label">Tunai</div>
                <div class="stat-number-sm">Rp {{ number_format($summary['tunai'], 0, ',', '.') }}</div>
                <div class="stat-sub">{{ $summary['count_tunai'] }} trx</div>
            </td>
            <td class="stat-card stat-card-blue" width="20%">
                <div class="stat-label">Transfer</div>
                <div class="stat-number-sm">Rp {{ number_format($summary['transfer'], 0, ',', '.') }}</div>
                <div class="stat-sub">{{ $summary['count_transfer'] }} trx</div>
            </td>
            <td class="stat-card stat-card-purple" width="20%">
                <div class="stat-label">QRIS</div>
                <div class="stat-number-sm">Rp {{ number_format($summary['qris'], 0, ',', '.') }}</div>
                <div class="stat-sub">{{ $summary['count_qris'] }} trx</div>
            </td>
        </tr>
    </table>

    {{-- ═══ RINGKASAN METODE PEMBAYARAN ═══ --}}
    <div class="section-title">Ringkasan Metode Pembayaran</div>

    <table class="method-table">
        <thead>
            <tr>
                <th width="25%">Metode</th>
                <th width="15%" style="text-align:center;">Jumlah Trx</th>
                <th width="30%" style="text-align:right;">Total</th>
                <th width="30%">Proporsi</th>
            </tr>
        </thead>
        <tbody>
            @php $maxMethod = max($summary['tunai'], $summary['transfer'], $summary['qris'], 1); @endphp
            <tr>
                <td><span class="method-badge badge-tunai">TUNAI</span></td>
                <td style="text-align:center; font-weight:bold;">{{ $summary['count_tunai'] }}</td>
                <td style="text-align:right; font-weight:bold;">Rp {{ number_format($summary['tunai'], 0, ',', '.') }}</td>
                <td>
                    <div class="bar-bg">
                        <div class="bar-fill-green" style="width: {{ $summary['total_pendapatan'] > 0 ? round(($summary['tunai'] / $summary['total_pendapatan']) * 100) : 0 }}%"></div>
                    </div>
                    <span style="font-size:7pt; color:#868e96;">{{ $summary['total_pendapatan'] > 0 ? round(($summary['tunai'] / $summary['total_pendapatan']) * 100, 1) : 0 }}%</span>
                </td>
            </tr>
            <tr>
                <td><span class="method-badge badge-transfer">TRANSFER</span></td>
                <td style="text-align:center; font-weight:bold;">{{ $summary['count_transfer'] }}</td>
                <td style="text-align:right; font-weight:bold;">Rp {{ number_format($summary['transfer'], 0, ',', '.') }}</td>
                <td>
                    <div class="bar-bg">
                        <div class="bar-fill-pink" style="width: {{ $summary['total_pendapatan'] > 0 ? round(($summary['transfer'] / $summary['total_pendapatan']) * 100) : 0 }}%"></div>
                    </div>
                    <span style="font-size:7pt; color:#868e96;">{{ $summary['total_pendapatan'] > 0 ? round(($summary['transfer'] / $summary['total_pendapatan']) * 100, 1) : 0 }}%</span>
                </td>
            </tr>
            <tr>
                <td><span class="method-badge badge-qris">QRIS</span></td>
                <td style="text-align:center; font-weight:bold;">{{ $summary['count_qris'] }}</td>
                <td style="text-align:right; font-weight:bold;">Rp {{ number_format($summary['qris'], 0, ',', '.') }}</td>
                <td>
                    <div class="bar-bg">
                        <div class="bar-fill-purple" style="width: {{ $summary['total_pendapatan'] > 0 ? round(($summary['qris'] / $summary['total_pendapatan']) * 100) : 0 }}%"></div>
                    </div>
                    <span style="font-size:7pt; color:#868e96;">{{ $summary['total_pendapatan'] > 0 ? round(($summary['qris'] / $summary['total_pendapatan']) * 100, 1) : 0 }}%</span>
                </td>
            </tr>
            <tr class="total-row">
                <td style="font-weight:bold;">TOTAL</td>
                <td style="text-align:center; font-weight:bold;">{{ $summary['total_transaksi'] }}</td>
                <td style="text-align:right; font-weight:bold; color:#970747; font-size:10pt;">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</td>
                <td><span style="font-size:7pt; color:#868e96;">100%</span></td>
            </tr>
        </tbody>
    </table>

    {{-- ═══ DETAIL TRANSAKSI ═══ --}}
    <div class="section-title">Detail Transaksi</div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="13%">Invoice</th>
                <th width="8%">Waktu</th>
                <th width="14%">Pasien</th>
                <th width="13%">Pemilik</th>
                <th width="10%">Metode</th>
                <th width="11%">Pemeriksaan</th>
                <th width="11%">Tindakan</th>
                <th width="11%">Obat</th>
                <th width="13%" style="text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $trx)
            <tr class="{{ $index % 2 == 1 ? 'even-row' : '' }}">
                <td style="text-align:center; color:#868e96; font-weight:bold;">{{ $index + 1 }}</td>
                <td>
                    <div class="invoice-text">{{ $trx->nomor_transaksi }}</div>
                </td>
                <td>
                    <div style="font-weight:600;">{{ $trx->tgl_bayar ? $trx->tgl_bayar->format('H:i') : '-' }}</div>
                    <div class="time-text">WIB</div>
                </td>
                <td>
                    <div class="pet-name">{{ $trx->medicalRecord->pet->nama_hewan ?? '-' }}</div>
                    @if($trx->medicalRecord->pet->jenis ?? null)
                        <span class="species-tag">{{ $trx->medicalRecord->pet->jenis }}</span>
                    @endif
                </td>
                <td>{{ $trx->medicalRecord->pet->owner->nama ?? '-' }}</td>
                <td>
                    <span class="method-badge {{ $trx->metode_bayar === 'tunai' ? 'badge-tunai' : ($trx->metode_bayar === 'transfer' ? 'badge-transfer' : 'badge-qris') }}">
                        {{ strtoupper($trx->metode_bayar ?? '-') }}
                    </span>
                </td>
                <td style="text-align:right;">
                    <div class="amount-detail">Rp {{ number_format($trx->biaya_pemeriksaan ?? 0, 0, ',', '.') }}</div>
                </td>
                <td style="text-align:right;">
                    <div class="amount-detail">Rp {{ number_format($trx->biaya_tindakan ?? 0, 0, ',', '.') }}</div>
                </td>
                <td style="text-align:right;">
                    <div class="amount-detail">Rp {{ number_format($trx->biaya_obat ?? 0, 0, ',', '.') }}</div>
                </td>
                <td style="text-align:right;">
                    <div class="amount-main">Rp {{ number_format($trx->total_biaya, 0, ',', '.') }}</div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="no-data">Tidak ada transaksi lunas pada periode ini</td>
            </tr>
            @endforelse
        </tbody>
        @if($transactions->count() > 0)
        <tfoot>
            <tr style="background:#f8f9fa;">
                <td colspan="6" style="text-align:right; font-weight:bold; padding:10px 8px; font-size:9pt;">TOTAL PENDAPATAN</td>
                <td style="text-align:right; font-weight:bold; border-top:2px solid #970747; padding:10px 8px;">
                    Rp {{ number_format($transactions->sum('biaya_pemeriksaan'), 0, ',', '.') }}
                </td>
                <td style="text-align:right; font-weight:bold; border-top:2px solid #970747; padding:10px 8px;">
                    Rp {{ number_format($transactions->sum('biaya_tindakan'), 0, ',', '.') }}
                </td>
                <td style="text-align:right; font-weight:bold; border-top:2px solid #970747; padding:10px 8px;">
                    Rp {{ number_format($transactions->sum('biaya_obat'), 0, ',', '.') }}
                </td>
                <td style="text-align:right; font-weight:bold; color:#970747; font-size:11pt; border-top:2px solid #970747; padding:10px 8px;">
                    Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- ═══ TANDA TANGAN ═══ --}}
    <table class="signature-section" cellpadding="0" cellspacing="0">
        <tr>
            <td width="50%">&nbsp;</td>
            <td width="50%">
                <div class="signature-label">Mengetahui,</div>
                <br><br><br><br>
                <div class="signature-line">{{ $admin_name }}</div>
                <div style="font-size:7.5pt; color:#868e96;">Admin Kasir</div>
            </td>
        </tr>
    </table>

    {{-- ═══ FOOTER ═══ --}}
    <div class="footer">
        Pet Area Clinic &bull; Laporan Transaksi Periode dihasilkan otomatis pada {{ $generated_at }} WIB &bull; <em>Confidential &mdash; Financial Report</em>
    </div>

</body>
</html>
