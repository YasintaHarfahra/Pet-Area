<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'name',
        'email',
        'username', // Pastikan ini ada
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi: User (dokter) memiliki banyak rekam medis
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'id_doctor');
    }

    // Relasi: User (admin) memiliki banyak transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_admin');
    }
}