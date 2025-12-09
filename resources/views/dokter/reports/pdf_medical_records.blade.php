<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekam Medis</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { font-size: 16px; margin-bottom: 5px; }
        p { font-size: 12px; margin-top: 0; }
    </style>
</head>
<body>
    <h1>Laporan Rekam Medis</h1>
    <p>Tanggal Cetak: {{ $date }}</p>
    <p>Rentang Waktu: {{ $range === 'all' ? 'Semua Data' : ($range === '1_month' ? '1 Bulan Terakhir' : '6 Bulan Terakhir') }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Pasien</th>
                <th>Pemilik</th>
                <th>Diagnosis</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $record)
            <tr>
                <td>{{ $record->created_at->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ $record->patient->name ?? '-' }}</strong><br>
                    <small>{{ $record->patient->species ?? '-' }} ({{ $record->patient->breed ?? '-' }})</small>
                </td>
                <td>{{ $record->patient->owner_name ?? '-' }}</td>
                <td>{{ $record->diagnosis }}</td>
                <td>{{ Str::limit($record->treatment, 100) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
