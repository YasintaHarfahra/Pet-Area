<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecordDraft extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_medical_record_draft';

    protected $table = 'medical_record_drafts';

    protected $fillable = [
        'id_queue',
        'id_doctor',
        'id_pet',
        'form_data',
        'status',
        'started_at',
        'last_updated_at',
    ];

    protected $casts = [
        'form_data' => 'array',
        'started_at' => 'datetime',
        'last_updated_at' => 'datetime',
    ];

    // Relationships
    public function queue()
    {
        return $this->belongsTo(Queue::class, 'id_queue');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'id_doctor');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'id_pet');
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in-progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('id_doctor', $doctorId);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['draft', 'in-progress']);
    }

    /**
     * Save draft data dari form
     */
    public static function saveDraft($queueId, $doctorId, $petId, $formData)
    {
        return self::updateOrCreate(
            [
                'id_queue' => $queueId,
                'id_doctor' => $doctorId,
            ],
            [
                'id_pet' => $petId,
                'form_data' => $formData,
                'status' => 'in-progress',
                'last_updated_at' => now(),
                'started_at' => self::where('id_queue', $queueId)->value('started_at') ?? now(),
            ]
        );
    }

    /**
     * Get active draft untuk queue tertentu
     */
    public static function getActiveDraft($queueId, $doctorId)
    {
        return self::where('id_queue', $queueId)
            ->where('id_doctor', $doctorId)
            ->whereIn('status', ['draft', 'in-progress'])
            ->first();
    }

    /**
     * Mark as completed dan return medical record ID
     */
    public function markAsCompleted($medicalRecordId)
    {
        $this->update([
            'status' => 'completed',
            'last_updated_at' => now(),
        ]);

        return $medicalRecordId;
    }

    /**
     * Get formatted form data
     */
    public function getFormDataAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Check if draft masih fresh (not older than 24 hours)
     */
    public function isFresh()
    {
        return $this->started_at->diffInHours(now()) < 24;
    }

    /**
     * Get duration sejak draft dimulai
     */
    public function getDurationMinutes()
    {
        return $this->started_at->diffInMinutes(now());
    }
}
