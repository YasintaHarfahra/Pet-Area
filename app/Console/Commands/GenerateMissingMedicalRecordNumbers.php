<?php
// app/Console/Commands/GenerateMissingMedicalRecordNumbers.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Patient; // Import model Patient
use Carbon\Carbon; // Import Carbon

class GenerateMissingMedicalRecordNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rm:generate-missing'; // Nama perintah di terminal: php artisan rm:generate-missing

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate medical record numbers for existing patients that do not have one.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mencari pasien tanpa Nomor RM...');

        // Ambil semua pasien yang medical_record_number-nya kosong atau null
        $patientsWithoutRm = Patient::whereNull('medical_record_number')->get();

        if ($patientsWithoutRm->isEmpty()) {
            $this->info('Semua pasien sudah memiliki Nomor RM. Tidak ada yang perlu digenerate.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($patientsWithoutRm as $patient) {
            // Generate Nomor RM dengan format RM-YYYYMMDD-IDPASIEN
            // Kita gunakan created_at pasien untuk tanggal jika tersedia, atau tanggal saat ini
            $datePart = $patient->created_at ? $patient->created_at->format('Ymd') : Carbon::now()->format('Ymd');
            $medicalRecordNumber = 'RM-' . $datePart . '-' . str_pad($patient->id, 4, '0', STR_PAD_LEFT);

            $patient->medical_record_number = $medicalRecordNumber;
            $patient->save();
            $count++;
            $this->info("Generated RM: {$medicalRecordNumber} for Patient ID: {$patient->id} ({$patient->name})");
        }

        $this->info("Selesai! {$count} Nomor RM berhasil digenerate.");
        return Command::SUCCESS;
    }
}