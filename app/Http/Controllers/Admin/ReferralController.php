<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Controller untuk Admin - Cetak Surat Rujukan
 */
class ReferralController extends Controller
{
    /**
     * Daftar surat rujukan
     */
    public function index(Request $request)
    {
        $query = Referral::with([
            'medicalRecord.pet.owner',
            'medicalRecord.doctor',
            'medicalRecord.prescriptionItems.medicine',
            'doctor',
            // 'animalMedicalRecord.animal.owner', // relasi ini sudah tidak ada
            // 'animalMedicalRecord.prescriptions.medicine', // jika sudah tidak dipakai, bisa dihapus
        ])->latest();

        // Filter status
        if ($status = $request->input('status')) {
            if ($status === 'pending') {
                $query->pending();
            } elseif ($status === 'printed') {
                $query->printed();
            }
        }

        $referrals = $query->paginate(15);

        // Stats (1 query gabungan)
        $statsRaw = \Illuminate\Support\Facades\DB::selectOne("
            SELECT
                COUNT(CASE WHEN status_cetak = 'pending' THEN 1 END) as pending,
                COUNT(CASE WHEN status_cetak = 'printed' THEN 1 END) as printed
            FROM referrals
        ");

        $stats = [
            'pending' => $statsRaw->pending ?? 0,
            'printed' => $statsRaw->printed ?? 0,
        ];

        return view('admin.referrals.index', compact('referrals', 'stats'));
    }

    /**
     * Detail surat rujukan
     */
    public function show(Referral $referral)
    {
        $referral->load([
            'medicalRecord.pet.owner',
            'medicalRecord.doctor',
            'medicalRecord.prescriptionItems.medicine',
            'doctor',
            // 'animalMedicalRecord.animal.owner', // relasi ini sudah tidak ada
            // 'animalMedicalRecord.prescriptions.medicine', // jika sudah tidak dipakai, bisa dihapus
        ]);
        
        return view('admin.referrals.show', compact('referral'));
    }

    /**
     * Cetak surat rujukan
     */
    public function print(Referral $referral)
    {
        $referral->load([
            'medicalRecord.pet.owner',
            'medicalRecord.doctor',
            'medicalRecord.prescriptionItems.medicine',
            'doctor',
            // 'animalMedicalRecord.animal.owner', // relasi ini sudah tidak ada
            // 'animalMedicalRecord.prescriptions.medicine', // jika sudah tidak dipakai, bisa dihapus
        ]);
        // Mark as printed
        if (!$referral->isPrinted()) {
            $referral->markAsPrinted(Auth::id());
        }
        // Generate QR code untuk tanda tangan dokter
        $dokterNama = $referral->doctor?->name ?? $referral->medicalRecord?->doctor?->name ?? '-';
        $dokterId = $referral->doctor?->id_user ?? $referral->medicalRecord?->id_doctor ?? '-';
        $nomorRujukan = $referral->nomor_rujukan ?? '-';
        $qrData = "SURAT RUJUKAN\n";
        $qrData .= "No: {$nomorRujukan}\n";
        $qrData .= "Dokter: {$dokterNama}\n";
        $qrData .= "ID Dokter: {$dokterId}\n";
        $qrData .= "Tanggal: " . ($referral->tanggal_rujukan ? $referral->tanggal_rujukan->format('d/m/Y') : now()->format('d/m/Y'));
        $qrCode = null;
        try {
            $qrCode = QrCode::format('svg')->size(100)->generate($qrData);
        } catch (\Exception $e) {
            Log::error('QR Code generation failed: ' . $e->getMessage());
            $qrCode = null;
        }
        return view('admin.referrals.print', compact('referral', 'qrCode', 'dokterNama'));
    }
}
