<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_queue';

    protected $fillable = [
        'id_pet',
        'patient_id',
        'id_doctor',
        'owner_id',
        'complaint',
        'status', // <--- WAJIB ADA INI!
        'notes',
        'queue_number',
        'queue_date',
    ];

    // Relasi ke rekam medis berdasarkan queue_id
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class, 'id_queue');
    }

    public function pet() {
        return $this->belongsTo(Pet::class, 'id_pet')->withTrashed();
    }
    
    public function doctor() {
        return $this->belongsTo(User::class, 'id_doctor');
    }
    // ... relasi lain ...

    /**
     * Generate the next queue number for a given date.
     * Format: Q-YYYYMMDD-NNN (e.g., Q-20240610-001)
     *
     * @param string $date (format: Y-m-d)
     * @return string
     */
    public static function generateQueueNumber($date)
    {
        // Count existing queues for the given date
        $count = self::where('queue_date', $date)->count();
        // Return the next integer queue number for the day
        return $count + 1;
    }
}