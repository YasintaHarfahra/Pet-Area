<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * Model Pet (Hewan Peliharaan)
 * 
 * @property int $id
 * @property string $no_rm - Nomor Rekam Medis (format: RM0001)
 * @property int $owner_id
 * @property string $nama_hewan
 * @property string $jenis
 * @property string|null $ras
 * @property \Carbon\Carbon|null $tgl_lahir
 * @property string|null $jenis_kelamin
 * @property string|null $catatan
 */
class Pet extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_pet';

    protected $fillable = [
        'id_owner',
        'no_rm',
        'nama_hewan',
        'jenis',
        'ras',
        'tgl_lahir',
        'jenis_kelamin',
        'catatan',
        'status_hidup',
        'tanggal_kematian',
        'penyebab_kematian',
        'foto_hewan',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'tanggal_kematian' => 'date',
    ];

    public const STATUS_HIDUP = 'hidup';
    public const STATUS_MATI = 'mati';

    /**
     * Kode jenis hewan untuk No. RM
     */
    public const JENIS_CODES = [
        'Kucing'  => 'K',
        'Anjing'  => 'A',
        'Kelinci' => 'L',
        'Hamster' => 'H',
        'Burung'  => 'B',
        'Reptil'  => 'R',
        'Lainnya' => 'X',
    ];

    /**
     * Boot method untuk auto-generate no_rm
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pet) {
            if (empty($pet->no_rm)) {
                $pet->no_rm = static::generateNoRM($pet->jenis);
            }
        });
    }

    /**
     * Generate nomor rekam medis otomatis
     * Format: RMK0001 (Kucing), RMA0001 (Anjing), dst
     */
    public static function generateNoRM(string $jenis): string
    {
        $code = self::JENIS_CODES[$jenis] ?? 'X';
        
        // Cari nomor terakhir untuk jenis ini (termasuk yang sudah soft-deleted)
        $lastPet = static::withTrashed()
                         ->where('no_rm', 'LIKE', "RM{$code}%")
                         ->orderByRaw("CAST(SUBSTRING(no_rm, 4) AS UNSIGNED) DESC")
                         ->first();
        
        if ($lastPet && preg_match('/RM[A-Z](\d+)/', $lastPet->no_rm, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        return 'RM' . $code . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Relasi: Pet dimiliki oleh Owner
     * withTrashed agar nama pemilik tetap tampil meski owner sudah dihapus
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class, 'id_owner')->withTrashed();
    }

    /**
     * Relasi: Pet memiliki banyak MedicalRecord
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'id_pet');
    }

    /**
     * Relasi: Rekam medis terakhir
     */
    public function latestMedicalRecord()
    {
        return $this->hasOne(MedicalRecord::class, 'id_pet')->latestOfMany('id_medical_record');
    }

    /**
     * Accessor: Hitung umur
     */
    public function getUmurAttribute()
    {
        if (!$this->tgl_lahir) {
            return null;
        }

        $lahir = Carbon::parse($this->tgl_lahir);
        $now = Carbon::now();
        
        $years = (int) $lahir->diffInYears($now);
        $months = (int) $lahir->copy()->addYears($years)->diffInMonths($now);
        
        if ($years > 0) {
            return $months > 0 ? "{$years} thn {$months} bln" : "{$years} tahun";
        }
        
        return "{$months} bulan";
    }

    /**
     * Accessor: Label jenis kelamin
     */
    public function getJenisKelaminLabelAttribute()
    {
        return match($this->jenis_kelamin) {
            'jantan' => '♂ Jantan',
            'betina' => '♀ Betina',
            default => '-'
        };
    }

    /**
     * Scope: Filter by jenis
     */
    public function scopeOfJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    /**
     * Scope: Search by name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_hewan', 'LIKE', "%{$search}%");
    }

    /**
     * Scope: Hanya hewan yang masih hidup/aktif.
     */
    public function scopeAlive($query)
    {
        return $query->where('status_hidup', self::STATUS_HIDUP);
    }

    /**
     * Scope: Hanya hewan yang sudah meninggal.
     */
    public function scopeDeceased($query)
    {
        return $query->where('status_hidup', self::STATUS_MATI);
    }

    /**
     * Accessor: true jika status hewan meninggal.
     */
    public function getIsDeceasedAttribute(): bool
    {
        return $this->status_hidup === self::STATUS_MATI;
    }

    /**
     * Accessor: URL foto hewan (null jika belum ada foto).
     */
    public function getFotoHewanUrlAttribute(): ?string
    {
        if (empty($this->foto_hewan)) {
            return null;
        }

        return asset('storage/' . ltrim($this->foto_hewan, '/'));
    }
}
