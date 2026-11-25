<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'admin_id',
        'visit_date',
        'purpose',
        'status',
        'notes',
    ];

    // Relasi: Kunjungan dimiliki oleh satu pasien
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    // Relasi: Kunjungan dicatat oleh satu admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}