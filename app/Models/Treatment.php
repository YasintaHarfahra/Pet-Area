<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatment extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_treatment';

    protected $fillable = [
        'nama_tindakan',
        'deskripsi',
        'harga',
        'kategori',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scope untuk mendapatkan treatment yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk filter berdasarkan kategori
    public function scopeByCategory($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Format harga ke Rupiah
     */
    public function getHargaFormatted()
    {
        return 'Rp' . number_format($this->harga, 0, ',', '.');
    }
}
