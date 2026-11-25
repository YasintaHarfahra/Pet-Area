<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model PrescriptionItem (Item Resep Obat)
 * 
 * @property int $id
 * @property int $medical_record_id
 * @property int $medicine_id
 * @property int $jumlah
 * @property float $harga_satuan
 * @property float $subtotal
 * @property string|null $instruksi
 */
class PrescriptionItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_prescription_item';

    protected $fillable = [
        'id_medical_record',
        'id_medicine',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'instruksi',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Boot model untuk auto-calculate subtotal dan kurangi stok
     */
    protected static function boot()
    {
        parent::boot();

        // Sebelum membuat item, hitung subtotal dan kurangi stok
        static::creating(function ($item) {
            // Ambil harga dari obat jika tidak diset
            if (empty($item->harga_satuan)) {
                $medicine = Medicine::find($item->id_medicine);
                if ($medicine) {
                    $item->harga_satuan = $medicine->harga;
                }
            }
            
            // Hitung subtotal
            $item->subtotal = $item->jumlah * $item->harga_satuan;
        });

        // Setelah item dibuat, kurangi stok
        static::created(function ($item) {
            $medicine = Medicine::find($item->id_medicine);
            if ($medicine) {
                $medicine->decreaseStock($item->jumlah);
            }
        });

        // Saat item dihapus, kembalikan stok
        static::deleted(function ($item) {
            $medicine = Medicine::find($item->id_medicine);
            if ($medicine) {
                $medicine->increaseStock($item->jumlah);
            }
        });
    }

    // ================================
    // RELASI
    // ================================

    /**
     * Relasi: Item belongs to MedicalRecord
     */
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'id_medical_record');
    }

    /**
     * Relasi: Item belongs to Medicine
     * withTrashed() agar nama obat tetap tampil meski obat sudah dihapus (soft delete)
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'id_medicine')->withTrashed();
    }

    // ================================
    // ACCESSORS
    // ================================

    /**
     * Format harga satuan
     */
    public function getFormattedHargaSatuanAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    /**
     * Format subtotal
     */
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
