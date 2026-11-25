<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_whats_app_log';

    // Pastikan nama kolom di sini SAMA PERSIS dengan di Migrasi database
    protected $fillable = [
        'nama_pemilik',
        'nama_hewan',
        'nomor_wa',
        'status',
    ];
}