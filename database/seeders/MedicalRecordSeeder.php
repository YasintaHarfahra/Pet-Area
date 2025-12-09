<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first patient and doctor
        $patient = Patient::first();
        $doctor = User::where('role', 'dokter')->first();
        
        if (!$patient || !$doctor) {
            $this->command->info('No patients or doctor found. Please run PatientSeeder and InitialUsersSeeder first.');
            return;
        }

        // Create sample medical records
        $medicalRecords = [
            [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'visit_date' => Carbon::now()->subDays(5),
                'symptoms' => 'Demam, tidak mau makan, lesu',
                'diagnosis' => 'Infeksi saluran pencernaan',
                'treatment' => 'Antibiotik dan probiotik',
                'prescription' => 'Amoxicillin 250mg 2x sehari, Probiotik 1x sehari',
                'notes' => 'Pasien perlu kontrol dalam 3 hari'
            ],
            [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'visit_date' => Carbon::now()->subDays(2),
                'symptoms' => 'Batuk, pilek, mata berair',
                'diagnosis' => 'Flu biasa',
                'treatment' => 'Istirahat dan vitamin',
                'prescription' => 'Vitamin C 1x sehari',
                'notes' => 'Kondisi membaik, lanjutkan pengobatan'
            ]
        ];

        foreach ($medicalRecords as $record) {
            MedicalRecord::create($record);
        }

        $this->command->info('Created ' . count($medicalRecords) . ' medical records successfully!');
    }
}
