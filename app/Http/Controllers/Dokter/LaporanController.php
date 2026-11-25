<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Exports\MedicalRecordsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan medis dengan filter.
     */
    public function index(Request $request)
    {
        $query = MedicalRecord::with(['pet' => fn($q) => $q->withTrashed(), 'pet.owner' => fn($q) => $q->withTrashed(), 'doctor', 'prescriptionItems.medicine', 'treatments']);
        $hasLegacyExamDate = Schema::hasColumn('medical_records', 'tgl_periksa');
        $hasDiagnosis = Schema::hasColumn('medical_records', 'diagnosis');
        $hasDiagnosa = Schema::hasColumn('medical_records', 'diagnosa');
        // Filter tanggal
        if ($request->filled('start_date')) {
            $startDate = $request->start_date;
            if ($hasLegacyExamDate) {
                $query->where(function ($q) use ($startDate) {
                    $q->whereDate('visit_date', '>=', $startDate)
                        ->orWhere(function ($sq) use ($startDate) {
                            $sq->whereNull('visit_date')
                                ->whereDate('tgl_periksa', '>=', $startDate);
                        });
                });
            } else {
                $query->whereDate('visit_date', '>=', $startDate);
            }
        }
        if ($request->filled('end_date')) {
            $endDate = $request->end_date;
            if ($hasLegacyExamDate) {
                $query->where(function ($q) use ($endDate) {
                    $q->whereDate('visit_date', '<=', $endDate)
                        ->orWhere(function ($sq) use ($endDate) {
                            $sq->whereNull('visit_date')
                                ->whereDate('tgl_periksa', '<=', $endDate);
                        });
                });
            } else {
                $query->whereDate('visit_date', '<=', $endDate);
            }
        }
        // Filter diagnosa
        if ($request->filled('diagnosis')) {
            $diagnosisKeyword = trim($request->diagnosis);
            if ($hasDiagnosis && $hasDiagnosa) {
                $query->where(function ($q) use ($diagnosisKeyword) {
                    $q->where('diagnosis', 'LIKE', "%{$diagnosisKeyword}%")
                        ->orWhere('diagnosa', 'LIKE', "%{$diagnosisKeyword}%");
                });
            } elseif ($hasDiagnosis) {
                $query->where('diagnosis', 'LIKE', "%{$diagnosisKeyword}%");
            } elseif ($hasDiagnosa) {
                $query->where('diagnosa', 'LIKE', "%{$diagnosisKeyword}%");
            }
        }
        // Filter dokter
        if ($request->filled('doctor_id')) {
            if ($request->doctor_id !== 'all') {
                $query->where('id_doctor', $request->doctor_id);
            }
        }
        // Filter nama hewan
        if ($request->filled('pet_name')) {
            $query->whereHas('pet', function($q) use ($request) {
                $q->where('nama_hewan', 'LIKE', "%{$request->pet_name}%");
            });
        }
        // Ambil statistik sesuai filter yang sama (cached 60 detik)
        $statsFilterQuery = clone $query;
        $statsRecords = $statsFilterQuery->get();
        $uniquePetIds = $statsRecords->pluck('id_pet')->unique();
        
        $statsQuery = (object) [
            'total_pasien' => $uniquePetIds->count(),
            'total_kucing' => $statsRecords->filter(fn($r) => $r->pet && $r->pet->jenis === 'Kucing')->pluck('id_pet')->unique()->count(),
            'total_anjing' => $statsRecords->filter(fn($r) => $r->pet && $r->pet->jenis === 'Anjing')->pluck('id_pet')->unique()->count(),
        ];

        // Diagnosa terbanyak dari data yang sudah difilter
        $topDiagnosis = $statsRecords
            ->map(function ($record) {
                $record->effective_diagnosis = trim((string) ($record->diagnosis ?? $record->diagnosa ?? ''));
                return $record;
            })
            ->filter(fn($r) => $r->effective_diagnosis !== '' && $r->effective_diagnosis !== '-')
            ->groupBy('effective_diagnosis')
            ->map(fn($g, $diagnosis) => (object) ['diagnosis' => $diagnosis, 'total' => $g->count()])
            ->sortByDesc('total')
            ->first();

        $records = $query->orderBy('visit_date', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total_pasien' => $statsQuery->total_pasien ?? 0,
            'total_kucing' => $statsQuery->total_kucing ?? 0,
            'total_anjing' => $statsQuery->total_anjing ?? 0,
            'diagnosa_terbanyak' => $topDiagnosis->diagnosis ?? null,
            'jumlah_diagnosa_terbanyak' => $topDiagnosis->total ?? null,
        ];

        $doctors = cache()->remember('dokter_list', 300, fn() => \App\Models\User::where('role', 'dokter')->get());
        return view('dokter.laporan.index', compact('records', 'stats', 'doctors'));
    }

    /**
     * Export laporan ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $filename = 'laporan_medis_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new MedicalRecordsExport($request->all()), $filename);
    }

    /**
     * Export laporan ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = MedicalRecord::with(['pet' => fn($q) => $q->withTrashed(), 'pet.owner' => fn($q) => $q->withTrashed(), 'doctor', 'prescriptionItems.medicine', 'treatments']);
        $hasLegacyExamDate = Schema::hasColumn('medical_records', 'tgl_periksa');
        $hasDiagnosis = Schema::hasColumn('medical_records', 'diagnosis');
        $hasDiagnosa = Schema::hasColumn('medical_records', 'diagnosa');
        if ($request->filled('start_date')) {
            $startDate = $request->start_date;
            if ($hasLegacyExamDate) {
                $query->where(function ($q) use ($startDate) {
                    $q->whereDate('visit_date', '>=', $startDate)
                        ->orWhere(function ($sq) use ($startDate) {
                            $sq->whereNull('visit_date')
                                ->whereDate('tgl_periksa', '>=', $startDate);
                        });
                });
            } else {
                $query->whereDate('visit_date', '>=', $startDate);
            }
        }
        if ($request->filled('end_date')) {
            $endDate = $request->end_date;
            if ($hasLegacyExamDate) {
                $query->where(function ($q) use ($endDate) {
                    $q->whereDate('visit_date', '<=', $endDate)
                        ->orWhere(function ($sq) use ($endDate) {
                            $sq->whereNull('visit_date')
                                ->whereDate('tgl_periksa', '<=', $endDate);
                        });
                });
            } else {
                $query->whereDate('visit_date', '<=', $endDate);
            }
        }
        if ($request->filled('diagnosis')) {
            $diagnosisKeyword = trim($request->diagnosis);
            if ($hasDiagnosis && $hasDiagnosa) {
                $query->where(function ($q) use ($diagnosisKeyword) {
                    $q->where('diagnosis', 'LIKE', "%{$diagnosisKeyword}%")
                        ->orWhere('diagnosa', 'LIKE', "%{$diagnosisKeyword}%");
                });
            } elseif ($hasDiagnosis) {
                $query->where('diagnosis', 'LIKE', "%{$diagnosisKeyword}%");
            } elseif ($hasDiagnosa) {
                $query->where('diagnosa', 'LIKE', "%{$diagnosisKeyword}%");
            }
        }
        if ($request->filled('doctor_id')) {
            if ($request->doctor_id !== 'all') {
                $query->where('id_doctor', $request->doctor_id);
            }
            // 'all' = no doctor filter
        }
        if ($request->filled('pet_name')) {
            $query->whereHas('pet', function($q) use ($request) {
                $q->where('nama_hewan', 'LIKE', "%{$request->pet_name}%");
            });
        }
        $records = $query->orderBy('visit_date', 'desc')->get()->map(function ($record) {
            if (empty($record->diagnosis) && !empty($record->diagnosa)) {
                $record->diagnosis = $record->diagnosa;
            }
            return $record;
        });

        // Data summary untuk PDF
        // Hitung pasien unik berdasarkan pet_id
        $uniquePetIds = $records->pluck('id_pet')->unique();
        $totalPasien = $uniquePetIds->count();
        // Hitung jumlah pasien unik per spesies
        $spesiesSummary = $records->whereIn('id_pet', $uniquePetIds)
            ->groupBy(function($item) {
                return $item->pet->jenis ?? '-';
            })
            ->map(function($group) {
                return $group->pluck('id_pet')->unique()->count();
            });
        $diagnosaSummary = $records->groupBy(function($item) {
            return strtolower(trim($item->diagnosis ?? '-'));
        })->map(function($group) {
            return $group->count();
        })->sortDesc();
        $diagnosaTerbanyak = $diagnosaSummary->filter(function($v, $k) { return $k !== '-' && $k !== ''; })->keys()->first();
        $jumlahDiagnosaTerbanyak = $diagnosaTerbanyak ? $diagnosaSummary[$diagnosaTerbanyak] : null;

        $pdf = Pdf::loadView('dokter.laporan.pdf', [
            'records' => $records,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'filter_diagnosis' => $request->diagnosis,
            'filter_pet_name' => $request->pet_name,
            'generated_at' => now()->format('d/m/Y H:i'),
            'doctor_name' => Auth::user()->name ?? '-',
            'totalPasien' => $totalPasien,
            'totalRekamMedis' => $records->count(),
            'spesiesSummary' => $spesiesSummary,
            'diagnosaSummary' => $diagnosaSummary->filter(fn($v, $k) => $k !== '-' && $k !== ''),
            'diagnosaTerbanyak' => $diagnosaTerbanyak,
            'jumlahDiagnosaTerbanyak' => $jumlahDiagnosaTerbanyak,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan_medis_' . now()->format('Y-m-d') . '.pdf');
    }
}
