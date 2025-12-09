<?php

namespace App\Exports;

use App\Models\MedicalRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class MedicalRecordsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $range;

    public function __construct($range)
    {
        $this->range = $range;
    }

    public function collection()
    {
        $query = MedicalRecord::with('patient')->orderBy('created_at', 'desc');

        if ($this->range === '1_month') {
            $query->where('created_at', '>=', Carbon::now()->subMonth());
        } elseif ($this->range === '6_months') {
            $query->where('created_at', '>=', Carbon::now()->subMonths(6));
        }

        return $query->get();
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
        return [
            $record->created_at->format('Y-m-d H:i'),
            $record->patient->medical_record_number ?? 'N/A',
            $record->patient->name ?? 'N/A',
            $record->patient->species ?? '-',
            $record->patient->breed ?? '-',
            $record->patient->gender ?? '-',
            $record->patient->owner_name ?? 'N/A',
            $record->patient->owner_phone ?? '-',
            $record->patient->owner_address ?? '-',
            $record->diagnosis,
            $record->symptoms,
            $record->treatment,
            $record->prescription,
            $record->notes,
        ];
    }
}
