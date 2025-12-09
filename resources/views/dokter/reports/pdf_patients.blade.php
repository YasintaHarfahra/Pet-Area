<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Pasien</title>
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
    <h1>Laporan Data Pasien</h1>
    <p>Tanggal Cetak: {{ $date }}</p>
    <p>Rentang Waktu: {{ $range === 'all' ? 'Semua Data' : ($range === '1_month' ? '1 Bulan Terakhir' : '6 Bulan Terakhir') }}</p>

    <table>
        <thead>
            <tr>
                <th>No RM</th>
                <th>Nama Pasien</th>
                <th>Jenis</th>
                <th>Pemilik</th>
                <th>Telepon</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $patient)
            <tr>
                <td>{{ $patient->medical_record_number }}</td>
                <td>
                    <strong>{{ $patient->name }}</strong><br>
                    <small>{{ $patient->gender }}</small>
                </td>
                <td>{{ $patient->species }} ({{ $patient->breed }})</td>
                <td>{{ $patient->owner_name }}</td>
                <td>{{ $patient->owner_phone }}</td>
                <td>{{ $patient->owner_address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
