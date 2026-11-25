<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Rujukan - {{ $referral->nomor_rujukan }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .page {
            width: 21cm;
            min-height: 29.7cm;
            padding: 2cm;
            margin: 0 auto;
            background: white;
        }
        
        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .clinic-name {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .clinic-address {
            font-size: 10pt;
            color: #555;
            margin-top: 5px;
        }
        
        /* Title */
        .title {
            text-align: center;
            margin: 30px 0;
        }
        
        .title h1 {
            font-size: 14pt;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        
        .title .nomor {
            font-size: 10pt;
            margin-top: 5px;
        }
        
        /* Content */
        .content {
            margin: 30px 0;
        }
        
        .greeting {
            margin-bottom: 20px;
        }
        
        .patient-info {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            background: #fafafa;
        }
        
        .patient-info table {
            width: 100%;
        }
        
        .patient-info td {
            padding: 3px 0;
            vertical-align: top;
        }
        
        .patient-info td:first-child {
            width: 150px;
            font-weight: bold;
        }
        
        .diagnosis-section,
        .referral-section {
            margin: 20px 0;
        }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .section-content {
            padding: 10px;
            border-left: 3px solid #333;
            background: #f9f9f9;
        }
        
        /* Footer */
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        
        .footer-left,
        .footer-right {
            text-align: center;
            width: 45%;
        }
        
        .signature-space {
            height: 80px;
        }
        
        .doctor-name {
            font-weight: bold;
            text-decoration: underline;
        }
        
        /* Print styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .page {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 1.5cm;
            }
            .no-print {
                display: none !important;
            }
        }
        
        /* Print button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .print-button:hover {
            background: #4338ca;
        }
        
        .back-button {
            position: fixed;
            top: 20px;
            right: 130px;
            padding: 12px 24px;
            background: #6b7280;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .back-button:hover {
            background: #4b5563;
        }
    </style>
</head>
<body>
    <a href="{{ route('admin.referrals.index') }}" class="no-print" style="
        position: fixed;
        top: 24px;
        right: 150px;
        display: inline-block;
        padding: 10px 28px;
        background: linear-gradient(90deg, #970747 0%, #7a0538 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(151,7,71,0.10);
        transition: background 0.2s, box-shadow 0.2s;
        text-decoration: none;
        letter-spacing: 0.5px;
    "
    onmouseover="this.style.background='#7a0538'"
    onmouseout="this.style.background='linear-gradient(90deg, #970747 0%, #7a0538 100%)'"
    >
        ← Kembali
    </a>
    <button onclick="window.print()" class="no-print" style="
        position: fixed;
        top: 24px;
        right: 24px;
        padding: 10px 28px;
        background: linear-gradient(90deg, #4f46e5 0%, #4338ca 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(79,70,229,0.10);
        transition: background 0.2s, box-shadow 0.2s;
        letter-spacing: 0.5px;
        cursor: pointer;
    "
    onmouseover="this.style.background='#4338ca'"
    onmouseout="this.style.background='linear-gradient(90deg, #4f46e5 0%, #4338ca 100%)'"
    >
        🖨 Cetak
    </button>

    <div class="page">
        {{-- Header Klinik --}}
        <div class="header" style="text-align: center; border-bottom: 3px double #333; padding-bottom: 15px; margin-bottom: 20px;">
            <div class="clinic-name">KLINIK HEWAN PET AREA</div>
            <div class="clinic-address">
                <p>Alamat Klinik</p>
                <p>Email: kontak@example.test</p>
            </div>
        </div>
        
        {{-- Title --}}
        <div class="title">
            <h1>Surat Rujukan</h1>
            <div class="nomor">Nomor: {{ $referral->nomor_rujukan }}</div>
        </div>
        
        {{-- Content --}}
        <div class="content">
            <div class="greeting">
                <p>Kepada Yth,</p>
                <p><strong>{{ $referral->tujuan_rujukan }}</strong></p>
                <p>di Tempat</p>
            </div>
            
            <p>Dengan hormat,</p>
            <p style="text-indent: 40px; text-align: justify;">
                Bersama surat ini kami merujuk pasien dengan data sebagai berikut untuk mendapatkan pemeriksaan dan penanganan lebih lanjut di tempat Bapak/Ibu:
            </p>

            @php
                // Data hewan & pemilik: dari MedicalRecord (pet clinic) atau AnimalMedicalRecord
                $pet = $referral->medicalRecord?->pet;
                $owner = $pet?->owner ?? $referral->animalMedicalRecord?->animal?->owner ?? null;
                $namaHewan = $pet?->nama_hewan ?? $referral->animalMedicalRecord?->animal?->nama_hewan ?? '-';
                $jenis = $pet?->jenis ?? $referral->animalMedicalRecord?->animal?->jenis ?? '-';
                $ras = $pet?->ras ?? $referral->animalMedicalRecord?->animal?->ras ?? '-';
                $jenisKelamin = $pet?->jenis_kelamin ?? $referral->animalMedicalRecord?->animal?->jenis_kelamin ?? '-';
                $umur = $pet?->umur ?? $referral->animalMedicalRecord?->animal?->umur ?? '-';
                $namaPemilik = $owner?->nama ?? '-';
                $noHpPemilik = $owner?->no_hp ?? '-';
                $alamatPemilik = $owner?->alamat ?? '-';
                // Diagnosa Sementara: ambil dari referral, jika kosong ambil dari medical record (diagnosis)
                $diagnosaTeks = $referral->diagnosa_sementara;
                if (empty($diagnosaTeks) || trim($diagnosaTeks) === '-' || trim($diagnosaTeks) === '') {
                    $diagnosaTeks = $referral->medicalRecord?->diagnosis ?? '-';
                }

                // Tindakan: ambil dari referral, jika kosong ambil dari relasi treatments pada medical record
                $tindakanTeks = $referral->tindakan_dilakukan;
                if (empty($tindakanTeks) || trim($tindakanTeks) === '-' || trim($tindakanTeks) === '') {
                    // Ambil dari relasi treatments (nama tindakan, join koma)
                    if ($referral->medicalRecord && $referral->medicalRecord->treatments && $referral->medicalRecord->treatments->count() > 0) {
                        $tindakanTeks = $referral->medicalRecord->treatments->pluck('nama_tindakan')->join(', ');
                    }
                }
                
                $dokterNama = $referral->doctor?->name ?? $referral->medicalRecord?->doctor?->name ?? '-';
                
                // Kumpulkan semua obat dari prescription_items (relasi ke medical_records)
                $allPrescriptions = collect();
                if ($referral->id_medical_record) {
                    try {
                        $prescriptionItems = \App\Models\PrescriptionItem::where('id_medical_record', $referral->id_medical_record)
                            ->with('medicine')
                            ->get();
                        if ($prescriptionItems && $prescriptionItems->count() > 0) {
                            foreach ($prescriptionItems as $item) {
                                if ($item->medicine) {
                                    $allPrescriptions->push((object)[
                                        'medicine' => $item->medicine,
                                        'id_medicine' => $item->id_medicine,
                                        'jumlah' => $item->jumlah,
                                        'instruksi' => $item->instruksi ?? '-',
                                    ]);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // Skip jika error
                    }
                }
                // Hapus duplikat berdasarkan medicine_id dan jumlah
                $allPrescriptions = $allPrescriptions->unique(function ($prescription) {
                    $medicineId = $prescription->id_medicine ?? ($prescription->medicine->id_medicine ?? 0);
                    $jumlah = $prescription->jumlah ?? 0;
                    return $medicineId . '_' . $jumlah;
                });
            @endphp
            
            {{-- Patient Info --}}
            <div class="patient-info">
                <table>
                    <tr>
                        <td>Nama Hewan</td>
                        <td>: {{ $namaHewan }}</td>
                    </tr>
                    <tr>
                        <td>Jenis / Ras</td>
                        <td>: {{ $jenis }} / {{ $ras }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>: {{ $jenisKelamin }}</td>
                    </tr>
                    <tr>
                        <td>Umur</td>
                        <td>: {{ $umur }}</td>
                    </tr>
                    <tr>
                        <td>Nama Pemilik</td>
                        <td>: {{ $namaPemilik }}</td>
                    </tr>
                    <tr>
                        <td>No. HP Pemilik</td>
                        <td>: {{ $noHpPemilik }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $alamatPemilik }}</td>
                    </tr>
                </table>
            </div>
            
            {{-- Diagnosis --}}
            <div class="diagnosis-section">
                <div class="section-title">Diagnosa Sementara:</div>
                <div class="section-content">
                    {{ $diagnosaTeks }}
                </div>
            </div>
            
            {{-- Treatment Done --}}
            @php
                // Pastikan tindakan selalu ditampilkan jika ada data
                $showTindakan = false;
                $finalTindakanTeks = null;
                
                if (!empty($tindakanTeks)) {
                    $trimmed = trim($tindakanTeks);
                    if ($trimmed !== '' && $trimmed !== '-') {
                        $showTindakan = true;
                        $finalTindakanTeks = $tindakanTeks;
                    }
                }
                
                // Jika masih tidak ada, coba ambil langsung dari referral tanpa proses
                if (!$showTindakan) {
                    $rawTindakan = $referral->tindakan_dilakukan ?? null;
                    if (!empty($rawTindakan) && trim($rawTindakan) !== '' && trim($rawTindakan) !== '-') {
                        $showTindakan = true;
                        $finalTindakanTeks = $rawTindakan;
                    }
                }
            @endphp
            
            @if($showTindakan && !empty($finalTindakanTeks))
            <div class="referral-section">
                <div class="section-title">Tindakan yang Sudah Dilakukan:</div>
                <div class="section-content">
                    {{ $finalTindakanTeks }}
                </div>
            </div>
            @endif

            {{-- Obat yang Sudah Diberikan --}}
            @php
                // Pastikan obat ditampilkan jika ada
                $hasObat = $allPrescriptions && $allPrescriptions->count() > 0;
                
                // Debug: Log untuk melihat apa yang terjadi
                // Log::info('PRESCRIPTIONS_DEBUG', [
                //     'count' => $allPrescriptions->count(),
                //     'medical_record_id' => $referral->medical_record_id,
                //     'animal_medical_record_id' => $referral->animal_medical_record_id,
                //     'prescriptions' => $allPrescriptions->toArray(),
                // ]);
            @endphp
            @if($hasObat)
            <div class="referral-section">
                <div class="section-title">Obat yang Sudah Diberikan:</div>
                <div class="section-content">
                    <ul style="list-style-type: none; padding-left: 0;">
                        @foreach($allPrescriptions as $prescription)
                            @php
                                // Handle berbagai format prescription object
                                $medicine = null;
                                $namaObat = '-';
                                $satuan = '';
                                $jumlah = 0;
                                $instruksi = null;
                                
                                // Cek apakah prescription adalah object dengan relasi medicine
                                if (is_object($prescription)) {
                                    // Jika punya relasi medicine langsung
                                    if (isset($prescription->medicine) && $prescription->medicine) {
                                        $medicine = $prescription->medicine;
                                    }
                                    // Jika punya id_medicine, load medicine
                                    elseif (isset($prescription->id_medicine) && $prescription->id_medicine) {
                                        try {
                                            $medicine = \App\Models\Medicine::find($prescription->id_medicine);
                                        } catch (\Exception $e) {
                                            $medicine = null;
                                        }
                                    }
                                    
                                    $jumlah = $prescription->jumlah ?? 0;
                                    $instruksi = $prescription->instruksi ?? null;
                                    
                                    // Jika masih belum ada medicine, coba ambil dari property langsung
                                    if (!$medicine && isset($prescription->id_medicine)) {
                                        try {
                                            $medicine = \App\Models\Medicine::find($prescription->id_medicine);
                                        } catch (\Exception $e) {
                                            $medicine = null;
                                        }
                                    }
                                }
                                
                                if ($medicine) {
                                    $namaObat = $medicine->nama_obat ?? $medicine->name ?? '-';
                                    $satuan = $medicine->satuan ?? $medicine->unit ?? '';
                                } else {
                                    // Fallback: jika tidak ada medicine, skip item ini
                                    $namaObat = null;
                                }
                            @endphp
                            @if($namaObat && $namaObat !== '-' && !empty(trim($namaObat)))
                            <li style="margin-bottom: 8px;">
                                <strong>{{ $namaObat }}</strong>
                                @if($jumlah > 0)
                                    - Jumlah: {{ $jumlah }} {{ $satuan }}
                                @endif
                                @if(!empty($instruksi))
                                    <br><span style="font-style: italic; color: #555;">Instruksi: {{ $instruksi }}</span>
                                @endif
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            
            {{-- Referral Reason --}}
            @if($referral->alasan_rujukan)
            <div class="referral-section">
                <div class="section-title">Alasan Rujukan:</div>
                <div class="section-content">
                    {{ $referral->alasan_rujukan }}
                </div>
            </div>
            @endif
            
            <p style="margin-top: 30px; text-align: justify;">
                Demikian surat rujukan ini kami buat, atas perhatian dan kerjasamanya kami ucapkan terima kasih.
            </p>
        </div>
        
        {{-- Footer with Signature --}}
        <div class="footer">
            <div class="footer-left">
                {{-- Empty space or stamp --}}
            </div>
            <div class="footer-right">
                <p>{{ $referral->tanggal_rujukan ? $referral->tanggal_rujukan->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</p>
                <p>Dokter yang merawat,</p>
                <div class="signature-space"></div>
                @php
                    // Debug: cek apakah QR code ada
                    $hasQrCode = isset($qrCode) && !empty($qrCode);
                @endphp
                @if($hasQrCode)
                    <div style="margin-bottom: 8px; text-align: center; display: inline-block;">
                        {!! $qrCode !!}
                    </div>
                @else
                    {{-- Fallback jika QR code tidak tersedia - generate ulang di view --}}
                    @php
                        try {
                            $qrDataFallback = "SURAT RUJUKAN\nNo: " . ($referral->nomor_rujukan ?? '-') . "\nDokter: {$dokterNama}\nTanggal: " . ($referral->tanggal_rujukan ? $referral->tanggal_rujukan->format('d/m/Y') : now()->format('d/m/Y'));
                            $qrCodeFallback = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($qrDataFallback);
                        } catch (\Exception $e) {
                            $qrCodeFallback = null;
                        }
                    @endphp
                    @if($qrCodeFallback)
                        <div style="margin-bottom: 8px; text-align: center; display: inline-block;">
                            {!! $qrCodeFallback !!}
                        </div>
                    @endif
                @endif
                <p class="doctor-name">{{ $dokterNama }}</p>
            </div>
        </div>
    </div>
</body>
</html>
