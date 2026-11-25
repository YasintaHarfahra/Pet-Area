<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\AnimalMedicalRecord;
use App\Models\Animal;
use App\Models\Owner;
use App\Models\Queue;
use App\Models\Referral;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DoctorDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware will be applied in routes
    }

    /**
     * Show the doctor dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Data untuk dashboard - dengan pengecekan tabel yang mungkin tidak ada
        $data = [
            'user' => Auth::user(),
            'totalPatients' => Schema::hasTable('patients') ? Patient::count() : 0,
            'newPatientsThisWeek' => Schema::hasTable('patients') ? Patient::where('created_at', '>=', now()->subWeek())->count() : 0,
            'totalMedicalRecords' => Schema::hasTable('medical_records') ? MedicalRecord::count() : 0,
            'updatedRecordsToday' => Schema::hasTable('medical_records') ? MedicalRecord::where('updated_at', '>=', now()->startOfDay())->count() : 0,
            'todayQueue' => Schema::hasTable('queues') ? Queue::whereDate('created_at', today())->count() : 0,
            'waitingQueue' => Schema::hasTable('queues') ? Queue::whereDate('created_at', today())->where('status', 'waiting')->count() : 0,
            'todayVisits' => 0, // Tabel visits tidak ada
            'pendingVisits' => 0, // Tabel visits tidak ada
            'currentQueue' => Schema::hasTable('queues') ? Queue::whereDate('created_at', today())
                ->where('status', 'waiting')
                ->with('patient')
                ->take(3)
                ->get() : collect([]),
            
            // Follow-up reminders data - dengan pengecekan method exists
            'followUpReminders' => $this->getFollowUpReminders(),
            'overdueFollowUps' => $this->getOverdueFollowUps(),
            'upcomingFollowUps' => $this->getUpcomingFollowUps(),
            
            // =========================================
            // STATISTIK: TOP 5 DIAGNOSA TERBANYAK
            // =========================================
            'topDiagnoses' => $this->getTopDiagnoses(),
            
            // Statistik sistem baru (Owner-based)
            'totalOwners' => Schema::hasTable('owners') ? Owner::count() : 0,
            'totalAnimals' => Schema::hasTable('animals') ? Animal::count() : 0,
            'totalAnimalRecords' => Schema::hasTable('animal_medical_records') ? AnimalMedicalRecord::count() : 0,
            
            // Rujukan pending untuk notifikasi
            'pendingReferrals' => Schema::hasTable('referrals') ? Referral::pending()->count() : 0,
        ];

        return view('dashboard-dokter', $data);
    }
    
    /**
     * Get follow-up reminders safely
     */
    private function getFollowUpReminders()
    {
        try {
            if (Schema::hasTable('medical_records') && method_exists(MedicalRecord::class, 'scopeNeedingFollowUp')) {
                return MedicalRecord::needingFollowUp(7, 1)
                    ->with(['patient', 'doctor'])
                    ->take(10)
                    ->get();
            }
        } catch (\Exception $e) {
            // Method tidak ada atau error lain
        }
        return collect([]);
    }
    
    /**
     * Get overdue follow-ups count safely
     */
    private function getOverdueFollowUps()
    {
        try {
            if (Schema::hasTable('medical_records') && method_exists(MedicalRecord::class, 'scopeOverdueFollowUp')) {
                return MedicalRecord::overdueFollowUp()
                    ->with(['patient', 'doctor'])
                    ->count();
            }
        } catch (\Exception $e) {
            // Method tidak ada atau error lain
        }
        return 0;
    }
    
    /**
     * Get upcoming follow-ups count safely
     */
    private function getUpcomingFollowUps()
    {
        try {
            if (Schema::hasTable('medical_records') && method_exists(MedicalRecord::class, 'scopeUpcomingFollowUp')) {
                return MedicalRecord::upcomingFollowUp(7)
                    ->with(['patient', 'doctor'])
                    ->count();
            }
        } catch (\Exception $e) {
            // Method tidak ada atau error lain
        }
        return 0;
    }
    
    /**
     * =========================================
     * GET TOP 5 DIAGNOSA TERBANYAK
     * =========================================
     * 
     * Query: Group by diagnosa field, count each, order by count DESC, limit 5
     * 
     * @return \Illuminate\Support\Collection
     */
    private function getTopDiagnoses()
    {
        // Query dari tabel animal_medical_records (sistem baru)
        $animalDiagnoses = AnimalMedicalRecord::select('diagnosa', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('diagnosa')
            ->where('diagnosa', '!=', '')
            ->groupBy('diagnosa')
            ->orderBy('jumlah', 'desc')
            ->limit(5)
            ->get();
        
        // Jika data dari sistem baru belum banyak, gabungkan dengan sistem lama
        if ($animalDiagnoses->count() < 5) {
            $legacyDiagnoses = MedicalRecord::select('diagnosis as diagnosa', DB::raw('COUNT(*) as jumlah'))
                ->whereNotNull('diagnosis')
                ->where('diagnosis', '!=', '')
                ->groupBy('diagnosis')
                ->orderBy('jumlah', 'desc')
                ->limit(5)
                ->get();
            
            // Gabungkan dan re-sort
            $combined = $animalDiagnoses->concat($legacyDiagnoses);
            
            // Group by diagnosa dan sum jumlah
            $grouped = $combined->groupBy('diagnosa')
                ->map(function ($items, $diagnosa) {
                    return (object)[
                        'diagnosa' => $diagnosa,
                        'jumlah' => $items->sum('jumlah')
                    ];
                })
                ->sortByDesc('jumlah')
                ->take(5)
                ->values();
            
            return $grouped;
        }
        
        return $animalDiagnoses;
    }
}