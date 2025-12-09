<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_number',
        'name',
        'species',
        'breed',
        'date_of_birth',
        'gender',
        'weight',
        'medical_history',
        'owner_name',
        'owner_phone',
        'owner_address',
        'owner_email',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // Relasi: Pasien memiliki banyak rekam medis
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    // Relasi: Pasien memiliki banyak kunjungan
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    // Relasi: Pasien bisa memiliki banyak transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Relasi: Pasien memiliki banyak antrian
    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    // Generate nomor rekam medis otomatis
    public static function generateMedicalRecordNumber()
    {
        $lastPatient = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastPatient ? (int)substr($lastPatient->medical_record_number, -5) : 0;
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        return 'RM-' . date('Ymd') . '-' . $newNumber;
    }
}