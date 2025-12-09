<?php

namespace App\Exports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PatientsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $range;

    public function __construct($range)
    {
        $this->range = $range;
    }

    public function collection()
    {
        $query = Patient::orderBy('created_at', 'desc');

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
            'Tanggal Terdaftar',
            'No RM',
            'Nama Pasien',
            'Jenis Hewan',
            'Ras',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Pemilik',
            'Telepon Pemilik',
            'Alamat Pemilik',
            'Riwayat Medis Awal',
        ];
    }

    public function map($patient): array
    {
        return [
            $patient->created_at->format('Y-m-d'),
            $patient->medical_record_number,
            $patient->name,
            $patient->species,
            $patient->breed,
            $patient->gender,
            $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '-',
            $patient->owner_name,
            $patient->owner_phone,
            $patient->owner_address,
            $patient->medical_history,
        ];
    }
}
