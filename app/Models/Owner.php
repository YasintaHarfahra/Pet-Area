<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Owner (Pemilik Hewan)
 * 
 * @property int $id
 * @property string $nama
 * @property string|null $alamat
 * @property string $no_hp
 * @property string|null $email
 */
class Owner extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_owner';

    protected $fillable = [
        'owner_code',
        'nama',
        'alamat',
        'no_hp',
        'email',
    ];

    /**
     * Boot: auto-generate owner_code saat membuat owner baru
     * Format: OWN-0001, OWN-0002, dst
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($owner) {
            if (empty($owner->owner_code)) {
                $owner->owner_code = static::generateOwnerCode();
            }
        });
    }

    /**
     * Generate kode pemilik otomatis (OWN-0001)
     */
    public static function generateOwnerCode(): string
    {
        $last = static::withTrashed()
            ->where('owner_code', 'LIKE', 'OWN-%')
            ->orderByRaw("CAST(SUBSTRING(owner_code, 5) AS UNSIGNED) DESC")
            ->value('owner_code');

        $next = 1;
        if ($last && preg_match('/OWN-(\d+)/', $last, $m)) {
            $next = intval($m[1]) + 1;
        }

        return 'OWN-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Relasi: Owner memiliki banyak Pet
     */
    public function pets()
    {
        return $this->hasMany(Pet::class, 'id_owner');
    }

    /**
     * Accessor: Format nomor HP untuk WhatsApp
     */
    public function getWhatsappLinkAttribute()
    {
        $phone = preg_replace('/[^0-9]/', '', $this->no_hp);
        
        // Konversi 08xx ke 628xx
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        
        return "https://wa.me/{$phone}";
    }

    /**
     * Accessor: Format display nomor HP
     */
    public function getFormattedPhoneAttribute()
    {
        $phone = $this->no_hp;
        
        // Jika dimulai dengan 62, format dengan +62
        if (str_starts_with($phone, '62')) {
            return '+' . $phone;
        }
        
        return $phone;
    }

    /**
     * Scope: Search by name or phone
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama', 'LIKE', "%{$search}%")
                     ->orWhere('no_hp', 'LIKE', "%{$search}%");
    }
}
