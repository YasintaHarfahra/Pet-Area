<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Model Transaction (Transaksi Pembayaran)
 * 
 * @property int $id
 * @property string $nomor_transaksi
 * @property int $medical_record_id
 * @property float $total_biaya
 * @property float $jumlah_bayar
 * @property float $kembalian
 * @property string $status_bayar
 * @property string|null $metode_bayar
 */
class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_transaction';

    protected $fillable = [
        'nomor_transaksi',
        'id_medical_record',
        'id_admin',
        'id_doctor',
        'id_pet',
        'total_biaya',
        'biaya_pemeriksaan',
        'biaya_tindakan',
        'biaya_obat',
        'jumlah_bayar',
        'kembalian',
        'status_bayar',
        'metode_bayar',
        'tgl_bayar',
        'catatan',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'total_biaya' => 'decimal:2',
        'jumlah_bayar' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'tgl_bayar' => 'datetime',
    ];

    /**
     * Boot model untuk auto-generate nomor transaksi
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->nomor_transaksi)) {
                $transaction->nomor_transaksi = self::generateNomorTransaksi();
            }
        });
    }

    /**
     * Generate nomor transaksi: TRX-YYMMDD-XXXX
     */
    public static function generateNomorTransaksi(): string
    {
        $prefix = 'TRX';
        $tanggal = Carbon::today()->format('ymd');
        
        $lastTransaction = self::where('nomor_transaksi', 'LIKE', "{$prefix}-{$tanggal}-%")
                               ->orderBy('nomor_transaksi', 'desc')
                               ->first();
        
        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->nomor_transaksi, -4);
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
     * Relasi: Transaction belongs to MedicalRecord
     */
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'id_medical_record');
    }

    /**
     * Relasi: Transaction belongs to Admin (User) yang memproses
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }

    // ================================
    // METHODS
    // ================================

    /**
     * Proses pembayaran
     */
    public function processPayment(float $jumlahBayar, string $metodeBayar, int $adminId): bool
    {
        if ($jumlahBayar < $this->total_biaya) {
            return false;
        }

        $this->update([
            'jumlah_bayar' => $jumlahBayar,
            'kembalian' => $jumlahBayar - $this->total_biaya,
            'status_bayar' => 'paid',
            'metode_bayar' => $metodeBayar,
            'id_admin' => $adminId,
            'tgl_bayar' => Carbon::now(),
        ]);

        return true;
    }

    // ================================
    // ACCESSORS
    // ================================

    /**
     * Format total biaya
     */
    public function getFormattedTotalBiayaAttribute()
    {
        return 'Rp ' . number_format($this->total_biaya, 0, ',', '.');
    }

    /**
     * Format jumlah bayar
     */
    public function getFormattedJumlahBayarAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_bayar, 0, ',', '.');
    }

    /**
     * Format kembalian
     */
    public function getFormattedKembalianAttribute()
    {
        return 'Rp ' . number_format($this->kembalian, 0, ',', '.');
    }

    /**
     * Badge status bayar
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status_bayar) {
            'paid' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>',
            'partial' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Sebagian</span>',
            default => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Bayar</span>'
        };
    }

    // ================================
    // SCOPES
    // ================================

    /**
     * Scope: Belum bayar (unpaid)
     */
    public function scopeBelumBayar($query)
    {
        return $query->where('status_bayar', 'unpaid');
    }

    /**
     * Scope: Lunas (paid)
     */
    public function scopeLunas($query)
    {
        return $query->where('status_bayar', 'paid');
    }

    /**
     * Scope: Today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tgl_bayar', Carbon::today());
    }
}