<?php

namespace App\Exports;

use App\Models\MedicalRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Schema;

class MedicalRecordsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = MedicalRecord::with([
            'pet' => fn($q) => $q->withTrashed(),
            'pet.owner' => fn($q) => $q->withTrashed(),
            'doctor',
            'treatments',
            'prescriptionItems.medicine',
        ]);
        $hasLegacyExamDate = Schema::hasColumn('medical_records', 'tgl_periksa');
        $hasDiagnosis = Schema::hasColumn('medical_records', 'diagnosis');
        $hasDiagnosa = Schema::hasColumn('medical_records', 'diagnosa');

        if (!empty($this->filters['start_date'])) {
            $startDate = $this->filters['start_date'];
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
        if (!empty($this->filters['end_date'])) {
            $endDate = $this->filters['end_date'];
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
        if (!empty($this->filters['diagnosis'])) {
            $diagnosisKeyword = trim($this->filters['diagnosis']);
            if ($hasDiagnosis && $hasDiagnosa) {
                $query->where(function ($q) use ($diagnosisKeyword) {
                    $q->where('diagnosis', 'LIKE', '%' . $diagnosisKeyword . '%')
                        ->orWhere('diagnosa', 'LIKE', '%' . $diagnosisKeyword . '%');
                });
            } elseif ($hasDiagnosis) {
                $query->where('diagnosis', 'LIKE', '%' . $diagnosisKeyword . '%');
            } elseif ($hasDiagnosa) {
                $query->where('diagnosa', 'LIKE', '%' . $diagnosisKeyword . '%');
            }
        }
        if (!empty($this->filters['doctor_id']) && $this->filters['doctor_id'] !== 'all') {
            $query->where('id_doctor', $this->filters['doctor_id']);
        }
        if (!empty($this->filters['pet_name'])) {
            $query->whereHas('pet', fn($q) => $q->where('nama_hewan', 'LIKE', '%' . $this->filters['pet_name'] . '%'));
        }

        return $query->orderBy('visit_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No RM',
            'Nama Pasien',
            'Jenis Hewan',
            'Ras',
            'Jenis Kelamin',
            'Pemilik',
            'Telepon Pemilik',
            'Alamat Pemilik',
            'Diagnosis',
            'Gejala',
            'Tindakan',
            'Resep',
            'Catatan',
        ];
    }

    public function map($record): array
    {
        $tindakan = $record->treatments->isNotEmpty()
            ? $record->treatments->pluck('nama_tindakan')->implode(', ')
            : '-';

        $resep = $record->prescriptionItems->isNotEmpty()
            ? $record->prescriptionItems->map(fn($item) =>
                ($item->medicine->nama_obat ?? '-') . ' (' . ($item->jumlah ?? $item->qty ?? 0) . ')'
              )->implode(', ')
            : '-';

        return [
            $record->visit_date
                ? $record->visit_date->format('Y-m-d H:i')
                : ($record->tgl_periksa ? $record->tgl_periksa . ' 00:00' : ($record->created_at ? $record->created_at->format('Y-m-d H:i') : '-')),
            $record->pet?->no_rm ?? '-',
            $record->pet?->nama_hewan ?? '-',
            $record->pet?->jenis ?? '-',
            $record->pet?->ras ?? '-',
            $record->pet?->jenis_kelamin ?? '-',
            $record->pet?->owner?->nama ?? '-',
            $record->pet?->owner?->no_hp ?? '-',
            $record->pet?->owner?->alamat ?? '-',
            ($record->diagnosis ?: $record->diagnosa) ?? '-',
            $record->symptoms ?? '-',
            $tindakan,
            $resep,
            $record->notes ?? '-',
        ];
    }
}
