<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Model Referral (Surat Rujukan)
 * 
 * @property int $id
 * @property string $nomor_rujukan
 * @property int $medical_record_id
 * @property string $tujuan_rujukan
 * @property string $alasan_rujukan
 * @property string $status_cetak
 */
class Referral extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_referral';

    protected $fillable = [
        'nomor_rujukan',
        'id_medical_record',
        'id_doctor',
        'tujuan_rujukan',
        'alasan_rujukan',
        'diagnosa_sementara',
        'tindakan_dilakukan',
        'tanggal_rujukan',
        'catatan',
        'status_cetak',
        'printed_at',
        'id_printed_by',
    ];

    protected $casts = [
        'tanggal_rujukan' => 'date',
        'printed_at' => 'datetime',
    ];

    /**
     * Boot model untuk auto-generate nomor rujukan
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($referral) {
            if (empty($referral->nomor_rujukan)) {
                $referral->nomor_rujukan = self::generateNomorRujukan();
            }
        });
    }

    /**
     * Generate nomor rujukan: RUJ-YYMMDD-XXXX
     */
    public static function generateNomorRujukan(): string
    {
        $prefix = 'RUJ';
        $tanggal = Carbon::today()->format('ymd');
        
        $lastReferral = self::where('nomor_rujukan', 'LIKE', "{$prefix}-{$tanggal}-%")
                            ->orderBy('nomor_rujukan', 'desc')
                            ->first();
        
        if ($lastReferral) {
            $lastNumber = (int) substr($lastReferral->nomor_rujukan, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return sprintf('%s-%s-%04d', $prefix, $tanggal, $newNumber);
    }

    // ================================
    // RELASI
    // ================================

    /**
     * Relasi: Referral belongs to MedicalRecord
     */
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'id_medical_record');
    }

    // Relasi ke AnimalMedicalRecord dihapus, hanya gunakan medicalRecord()

    /**
     * Relasi: Dokter yang merujuk (User)
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'id_doctor');
    }

    /**
     * Relasi: Dicetak oleh User
     */
    public function printedByUser()
    {
        return $this->belongsTo(User::class, 'id_printed_by');
    }

    // ================================
    // METHODS
    // ================================

    /**
     * Mark as printed
     */
    public function markAsPrinted(?int $userId = null): void
    {
        $this->update([
            'status_cetak' => 'printed',
            'printed_at' => Carbon::now(),
            'id_printed_by' => $userId ?? Auth::id(),
        ]);
    }

    /**
     * Check if printed
     */
    public function isPrinted(): bool
    {
        return $this->status_cetak === 'printed';
    }

    // ================================
    // ACCESSORS
    // ================================

    /**
     * Badge status cetak
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status_cetak) {
            'printed' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Sudah Dicetak</span>',
            default => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Dicetak</span>'
        };
    }

    // ================================
    // SCOPES
    // ================================

    /**
     * Scope: Pending (belum dicetak)
     */
    public function scopePending($query)
    {
        return $query->where('status_cetak', 'pending');
    }

    /**
     * Scope: Printed (sudah dicetak)
     */
    public function scopePrinted($query)
    {
        return $query->where('status_cetak', 'printed');
    }
}
