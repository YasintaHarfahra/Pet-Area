<?php
/**
 * =====================================================
 * CONTROLLER: ReferralController (SURAT RUJUKAN)
 * =====================================================
 * 
 * Controller untuk Admin mengelola dan mencetak surat rujukan.
 * Dokter membuat rujukan dari form rekam medis, Admin mencetak.
 * =====================================================
 */

namespace App\Http\Controllers;

use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReferralController extends Controller
{
    /**
     * Display listing of referrals (for Admin)
     */
    public function index(Request $request)
    {
        $query = Referral::with(['animalMedicalRecord.animal.owner', 'doctor'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status_cetak', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_rujukan', 'like', "%{$search}%")
                  ->orWhere('tujuan_rujukan', 'like', "%{$search}%")
                  ->orWhereHas('animalMedicalRecord.animal', function ($q2) use ($search) {
                      $q2->where('nama_hewan', 'like', "%{$search}%");
                  })
                  ->orWhereHas('animalMedicalRecord.animal.owner', function ($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $referrals = $query->paginate(15);

        return view('admin.referrals.index', compact('referrals'));
    }

    /**
     * Show single referral
     */
    public function show(Referral $referral)
    {
        $referral->load(['animalMedicalRecord.animal.owner', 'doctor']);
        return view('admin.referrals.show', compact('referral'));
    }

    /**
     * Print referral (generate print view)
     */
    public function print(Referral $referral)
    {
        $referral->load(['animalMedicalRecord.animal.owner', 'doctor']);
        // Mark as printed
        if (!$referral->isPrinted()) {
            $referral->markAsPrinted(Auth::id());
        }
        // Generate QR code untuk ttd dokter
        $dokterNama = $referral->doctor?->name ?? '-';
        $dokterId = $referral->doctor?->id_user ?? '-';
        $qrData = "Dokter: $dokterNama\nID: $dokterId";
        $qrCode = QrCode::format('svg')->size(100)->generate($qrData);
        return view('admin.referrals.print', compact('referral', 'qrCode', 'dokterNama'));
    }

    /**
     * Mark referral as printed (AJAX)
     */
    public function markPrinted(Referral $referral)
    {
        $referral->markAsPrinted(Auth::id());
        
        return response()->json([
            'success' => true,
            'message' => 'Rujukan berhasil ditandai sebagai sudah dicetak'
        ]);
    }

    /**
     * Get pending referrals count (for dashboard widget)
     */
    public function getPendingCount()
    {
        $count = Referral::pending()->count();
        return response()->json(['count' => $count]);
    }
}
