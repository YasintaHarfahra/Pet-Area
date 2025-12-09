<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'queue_number',
        'status',
        'queue_date',
        'complaint',
        'medical_record_id'
    ];

    protected $dates = [
        'queue_date',
        'created_at',
        'updated_at'
    ];

    // Relasi dengan Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relasi dengan MedicalRecord (jika nanti dibuat)
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    // Scope untuk mendapatkan antrian hari ini
    public function scopeToday($query)
    {
        return $query->whereDate('queue_date', today());
    }

    // Scope untuk antrian yang masih menunggu
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    // Scope untuk antrian yang sedang dalam proses
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in-progress');
    }

    // Generate nomor antrian untuk hari ini
    public static function generateQueueNumber($date)
    {
        $lastQueue = self::whereDate('queue_date', $date)
            ->orderBy('queue_number', 'desc')
            ->first();

        return $lastQueue ? $lastQueue->queue_number + 1 : 1;
    }
}