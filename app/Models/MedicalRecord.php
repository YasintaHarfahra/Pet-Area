<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_medical_record';

    /**
     * Hitung dan simpan total biaya rekam medis
     */
    public function calculateTotalBiaya()
    {
        $biayaPemeriksaan = $this->biaya_pemeriksaan ?? 0;
        $biayaTindakan = $this->biaya_tindakan ?? 0;
        $biayaObat = $this->biaya_obat ?? 0;
        $total = $biayaPemeriksaan + $biayaTindakan + $biayaObat;
        $this->total_biaya = $total;
        $this->save();
    }

    protected $table = 'medical_records';

    protected $fillable = [
        'id_pet',
        'id_doctor',
        'id_queue',
        'visit_date',
        'symptoms',
        'diagnosis',
        'prescription',
        'berat_badan',
        'suhu_tubuh',
        'next_control_date',
        'follow_up_notes',
        'biaya_pemeriksaan',
        'biaya_tindakan',
        'biaya_obat',
        'total_biaya',
        'status'
    ];

    // Scope untuk filter berdasarkan dokter
    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('id_doctor', $doctorId);
    }

    protected $casts = [
        'visit_date' => 'datetime',
        'next_control_date' => 'date',
    ];

    // Relasi Many-to-Many untuk Tindakan
    // withTrashed() agar tindakan yang sudah dihapus (soft delete) tetap tampil di riwayat
    public function treatments()
    {
        return $this->belongsToMany(Treatment::class, 'medical_record_treatments', 'id_medical_record', 'id_treatment')
            ->withPivot('harga')
            ->withTrashed();
    }

    public function pet() { return $this->belongsTo(Pet::class, 'id_pet')->withTrashed(); }
    public function doctor() { return $this->belongsTo(User::class, 'id_doctor'); }
    public function prescriptionItems() { return $this->hasMany(PrescriptionItem::class, 'id_medical_record'); }
    public function referral() { return $this->hasOne(Referral::class, 'id_medical_record'); }
}