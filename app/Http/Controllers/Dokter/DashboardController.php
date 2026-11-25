<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Queue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $doctorId = Auth::id();
        $today = Carbon::today();
        $hasDiagnosis = Schema::hasColumn('medical_records', 'diagnosis');
        $hasDiagnosa = Schema::hasColumn('medical_records', 'diagnosa');

        $statsResolver = function () use ($doctorId, $today) {
            $statsRaw = DB::table('medical_records')
                ->selectRaw("
                    COUNT(CASE WHEN DATE(visit_date) = ? AND id_doctor = ? THEN 1 END) as pemeriksaan_hari_ini,
                    COUNT(CASE WHEN next_control_date >= ? AND id_doctor = ? THEN 1 END) as kontrol_mendatang,
                    (SELECT COUNT(*) FROM pets) as total_pasien
                ", [$today, $doctorId, $today, $doctorId])
                ->first();

            return [
                'pemeriksaan_hari_ini' => $statsRaw->pemeriksaan_hari_ini ?? 0,
                'total_pasien' => $statsRaw->total_pasien ?? 0,
                'kontrol_mendatang' => $statsRaw->kontrol_mendatang ?? 0,
            ];
        };

        $dashboardTtl = (int) config('cache.dashboard_ttl', 60);
        $stats = $dashboardTtl > 0
            ? cache()->remember("dokter_stats_{$doctorId}_{$today->toDateString()}", $dashboardTtl, $statsResolver)
            : $statsResolver();

        // Antrian hari ini
        $todayQueues = Queue::with(['pet.owner'])
            ->where('queue_date', $today->toDateString())
            ->orderByRaw("FIELD(status, 'in_progress', 'waiting', 'completed', 'skipped', 'cancelled')")
            ->orderBy('queue_number')
            ->get();

        // Rekam medis terbaru (5 terakhir)
        $recentRecords = MedicalRecord::with(['pet.owner', 'treatments'])
            ->byDoctor($doctorId)
            ->latest('visit_date')
            ->limit(5)
            ->get();

        // Top 5 Diagnosa (dinormalisasi agar beda kapital/spasi tidak terpisah)
        $topDiagnosesQuery = MedicalRecord::query();

        if ($hasDiagnosis && $hasDiagnosa) {
            $topDiagnosesRaw = $topDiagnosesQuery
                ->selectRaw("LOWER(TRIM(COALESCE(NULLIF(diagnosis, ''), NULLIF(diagnosa, '')))) as diagnosis")
                ->selectRaw('COUNT(*) as total')
                ->where(function ($query) {
                    $query->whereNotNull('diagnosis')->where('diagnosis', '!=', '')
                        ->orWhere(function ($subQuery) {
                            $subQuery->whereNotNull('diagnosa')->where('diagnosa', '!=', '');
                        });
                })
                ->groupBy('diagnosis')
                ->orderByDesc('total')
                ->limit(5)
                ->get();
        } elseif ($hasDiagnosis) {
            $topDiagnosesRaw = $topDiagnosesQuery
                ->selectRaw("LOWER(TRIM(diagnosis)) as diagnosis")
                ->selectRaw('COUNT(*) as total')
                ->whereNotNull('diagnosis')
                ->where('diagnosis', '!=', '')
                ->groupBy('diagnosis')
                ->orderByDesc('total')
                ->limit(5)
                ->get();
        } elseif ($hasDiagnosa) {
            $topDiagnosesRaw = $topDiagnosesQuery
                ->selectRaw("LOWER(TRIM(diagnosa)) as diagnosis")
                ->selectRaw('COUNT(*) as total')
                ->whereNotNull('diagnosa')
                ->where('diagnosa', '!=', '')
                ->groupBy('diagnosis')
                ->orderByDesc('total')
                ->limit(5)
                ->get();
        } else {
            $topDiagnosesRaw = collect();
        }

        $topDiagnoses = $topDiagnosesRaw->filter(fn($item) => !empty($item->diagnosis));

        // Reminder kontrol (dalam 7 hari ke depan)
        $kontrolReminders = MedicalRecord::with(['pet.owner'])
            ->byDoctor($doctorId)
            ->whereBetween('next_control_date', [$today, $today->copy()->addDays(7)])
            ->orderBy('next_control_date')
            ->get();

        return view('dokter.dashboard', compact(
            'stats',
            'todayQueues',
            'recentRecords',
            'topDiagnoses',
            'kontrolReminders'
        ));
    }
}
