<?php
/**
 * =====================================================
 * MODEL: PRESCRIPTION (RESEP OBAT)
 * =====================================================
 * 
 * Model untuk mengelola resep obat dari rekam medis.
 * Saat resep disimpan, stok obat akan berkurang otomatis.
 * 
 * RELASI:
 * - Prescription belongsTo AnimalMedicalRecord
 * - Prescription belongsTo Medicine
 * =====================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $table = 'prescriptions';

    protected $fillable = [
        'animal_medical_record_id',
        'medical_record_id',
        'medicine_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'instruksi',
        'catatan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * =========================================
     * RELASI: belongsTo AnimalMedicalRecord
     * =========================================
     */
    public function animalMedicalRecord()
    {
        return $this->belongsTo(AnimalMedicalRecord::class, 'animal_medical_record_id');
    }

    /**
     * =========================================
     * RELASI: belongsTo MedicalRecord (legacy)
     * =========================================
     */
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'medical_record_id');
    }

    /**
     * =========================================
     * RELASI: belongsTo Medicine
     * =========================================
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    /**
     * =========================================
     * Boot method: Auto-calculate subtotal & reduce stock
     * =========================================
     */
    protected static function boot()
    {
        parent::boot();

        // Saat membuat prescription baru
        static::creating(function ($prescription) {
            // Ambil harga dari obat jika belum diset
            if (!$prescription->harga_satuan && $prescription->medicine_id) {
                $medicine = Medicine::find($prescription->medicine_id);
                if ($medicine) {
                    $prescription->harga_satuan = $medicine->harga_jual;
                }
            }
            
            // Hitung subtotal
            $prescription->subtotal = $prescription->jumlah * $prescription->harga_satuan;
        });

        // Setelah prescription dibuat, kurangi stok
        static::created(function ($prescription) {
            $prescription->reduceStock();
        });

        // Saat prescription dihapus, kembalikan stok
        static::deleting(function ($prescription) {
            $prescription->restoreStock();
        });
    }

    /**
     * Kurangi stok obat
     */
    public function reduceStock()
    {
        $medicine = $this->medicine;
        if ($medicine) {
            $medicine->decrement('stok', $this->jumlah);
        }
    }

    /**
     * Kembalikan stok obat (saat prescription dihapus)
     */
    public function restoreStock()
    {
        $medicine = $this->medicine;
        if ($medicine) {
            $medicine->increment('stok', $this->jumlah);
        }
    }
}
