<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Medicine (Obat-obatan)
 * 
 * @property int $id
 * @property string $nama_obat
 * @property string $satuan
 * @property int $stok
 * @property int $stok_minimum
 * @property float $harga_jual
 * @property string|null $keterangan
 * @property bool $is_active
 */
class Medicine extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        $clearCache = function () {
            self::clearDashboardStatsCache();
            cache()->forget('all_medicines');
        };

        static::saved($clearCache);
        static::deleted($clearCache);
        static::restored($clearCache);
        static::forceDeleted($clearCache);
    }

    protected $primaryKey = 'id_medicine';

    protected $fillable = [
        'nama_obat',
        'dosis',
        'satuan',
        'stok',
        'stok_minimum',
        'harga_jual',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Alias harga -> harga_jual untuk kompatibilitas
    public function getHargaAttribute()
    {
        return $this->harga_jual;
    }

    /**
     * Relasi: Medicine digunakan di banyak PrescriptionItem
     */
    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class, 'id_medicine');
    }

    /**
     * Accessor: Format harga
     */
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Accessor: Check apakah stok rendah
     */
    public function getIsLowStockAttribute()
    {
        return $this->stok <= $this->stok_minimum;
    }

    /**
     * Accessor: Badge status stok
     */
    public function getStockBadgeAttribute()
    {
        if ($this->stok <= 0) {
            return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Habis</span>';
        }
        
        if ($this->is_low_stock) {
            return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Stok Rendah</span>';
        }
        
        return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>';
    }

    /**
     * Kurangi stok
     */
    public function decreaseStock(int $amount): bool
    {
        if ($this->stok < $amount) {
            return false;
        }

        $this->decrement('stok', $amount);
        cache()->forget('all_medicines');
        cache()->forget('active_medicines');
        self::clearDashboardStatsCache();
        return true;
    }

    /**
     * Tambah stok
     */
    public function increaseStock(int $amount): void
    {
        $this->increment('stok', $amount);
        cache()->forget('all_medicines');
        cache()->forget('active_medicines');
        self::clearDashboardStatsCache();
    }

    /**
     * Scope: Hanya yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Stok tersedia
     */
    public function scopeInStock($query)
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Scope: Stok rendah
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stok', '<=', 'stok_minimum');
    }

    /**
     * Scope: Search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_obat', 'LIKE', "%{$search}%");
    }

    /**
     * Hapus cache statistik dashboard admin agar perubahan stok langsung terlihat.
     */
    private static function clearDashboardStatsCache(): void
    {
        cache()->forget('admin_dashboard_stats_' . now()->toDateString());
    }
}
