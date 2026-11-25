<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * =============================================================
 * MIGRATION: Rename semua kolom ID menjadi nama deskriptif
 * =============================================================
 * 
 * Contoh: users.id → users.id_user
 *         pets.owner_id → pets.id_owner
 * 
 * Untuk keperluan dokumentasi skripsi agar lebih jelas
 * =============================================================
 */
return new class extends Migration
{
    /**
     * Helper: rename column jika kolom lama ada dan kolom baru belum ada
     */
    private function safeRename(string $table, string $from, string $to): void
    {
        if (Schema::hasColumn($table, $from) && !Schema::hasColumn($table, $to)) {
            Schema::table($table, function (\Illuminate\Database\Schema\Blueprint $t) use ($from, $to) {
                $t->renameColumn($from, $to);
            });
        }
    }

    public function up(): void
    {
        // =============================================
        // 1. RENAME PRIMARY KEYS (id → id_xxx)
        // =============================================
        $this->safeRename('users', 'id', 'id_user');
        $this->safeRename('owners', 'id', 'id_owner');
        $this->safeRename('pets', 'id', 'id_pet');
        $this->safeRename('medical_records', 'id', 'id_medical_record');
        $this->safeRename('queues', 'id', 'id_queue');
        $this->safeRename('transactions', 'id', 'id_transaction');
        $this->safeRename('treatments', 'id', 'id_treatment');
        $this->safeRename('medicines', 'id', 'id_medicine');
        $this->safeRename('prescription_items', 'id', 'id_prescription_item');
        $this->safeRename('referrals', 'id', 'id_referral');
        $this->safeRename('medical_record_drafts', 'id', 'id_medical_record_draft');
        $this->safeRename('medical_record_treatments', 'id', 'id_medical_record_treatment');
        $this->safeRename('whats_app_logs', 'id', 'id_whats_app_log');

        // =============================================
        // 2. RENAME FOREIGN KEYS
        // =============================================

        // -- pets --
        $this->safeRename('pets', 'owner_id', 'id_owner');

        // -- medical_records --
        $this->safeRename('medical_records', 'pet_id', 'id_pet');
        $this->safeRename('medical_records', 'doctor_id', 'id_doctor');
        $this->safeRename('medical_records', 'queue_id', 'id_queue');

        // -- queues --
        $this->safeRename('queues', 'pet_id', 'id_pet');
        $this->safeRename('queues', 'doctor_id', 'id_doctor');
        $this->safeRename('queues', 'medical_record_id', 'id_medical_record');

        // -- transactions --
        $this->safeRename('transactions', 'pet_id', 'id_pet');
        $this->safeRename('transactions', 'admin_id', 'id_admin');
        $this->safeRename('transactions', 'doctor_id', 'id_doctor');
        $this->safeRename('transactions', 'medical_record_id', 'id_medical_record');
        $this->safeRename('transactions', 'cashier_id', 'id_cashier');

        // -- prescription_items --
        $this->safeRename('prescription_items', 'medical_record_id', 'id_medical_record');
        $this->safeRename('prescription_items', 'medicine_id', 'id_medicine');

        // -- referrals --
        $this->safeRename('referrals', 'medical_record_id', 'id_medical_record');
        $this->safeRename('referrals', 'doctor_id', 'id_doctor');
        $this->safeRename('referrals', 'printed_by', 'id_printed_by');

        // -- medical_record_drafts --
        $this->safeRename('medical_record_drafts', 'queue_id', 'id_queue');
        $this->safeRename('medical_record_drafts', 'doctor_id', 'id_doctor');
        $this->safeRename('medical_record_drafts', 'pet_id', 'id_pet');

        // -- medical_record_treatments (pivot) --
        $this->safeRename('medical_record_treatments', 'medical_record_id', 'id_medical_record');
        $this->safeRename('medical_record_treatments', 'treatment_id', 'id_treatment');

        // -- sessions --
        $this->safeRename('sessions', 'user_id', 'id_user');
    }

    public function down(): void
    {
        // =============================================
        // ROLLBACK: Kembalikan semua nama kolom
        // =============================================

        // -- sessions --
        $this->safeRename('sessions', 'id_user', 'user_id');

        // -- medical_record_treatments --
        $this->safeRename('medical_record_treatments', 'id_medical_record', 'medical_record_id');
        $this->safeRename('medical_record_treatments', 'id_treatment', 'treatment_id');

        // -- medical_record_drafts --
        $this->safeRename('medical_record_drafts', 'id_pet', 'pet_id');
        $this->safeRename('medical_record_drafts', 'id_doctor', 'doctor_id');
        $this->safeRename('medical_record_drafts', 'id_queue', 'queue_id');

        // -- referrals --
        $this->safeRename('referrals', 'id_printed_by', 'printed_by');
        $this->safeRename('referrals', 'id_doctor', 'doctor_id');
        $this->safeRename('referrals', 'id_medical_record', 'medical_record_id');

        // -- prescription_items --
        $this->safeRename('prescription_items', 'id_medicine', 'medicine_id');
        $this->safeRename('prescription_items', 'id_medical_record', 'medical_record_id');

        // -- transactions --
        $this->safeRename('transactions', 'id_cashier', 'cashier_id');
        $this->safeRename('transactions', 'id_medical_record', 'medical_record_id');
        $this->safeRename('transactions', 'id_doctor', 'doctor_id');
        $this->safeRename('transactions', 'id_admin', 'admin_id');
        $this->safeRename('transactions', 'id_pet', 'pet_id');

        // -- queues --
        $this->safeRename('queues', 'id_medical_record', 'medical_record_id');
        $this->safeRename('queues', 'id_doctor', 'doctor_id');
        $this->safeRename('queues', 'id_pet', 'pet_id');

        // -- medical_records --
        $this->safeRename('medical_records', 'id_queue', 'queue_id');
        $this->safeRename('medical_records', 'id_doctor', 'doctor_id');
        $this->safeRename('medical_records', 'id_pet', 'pet_id');

        // -- pets --
        $this->safeRename('pets', 'id_owner', 'owner_id');

        // -- Primary Keys --
        $this->safeRename('whats_app_logs', 'id_whats_app_log', 'id');
        $this->safeRename('medical_record_treatments', 'id_medical_record_treatment', 'id');
        $this->safeRename('medical_record_drafts', 'id_medical_record_draft', 'id');
        $this->safeRename('referrals', 'id_referral', 'id');
        $this->safeRename('prescription_items', 'id_prescription_item', 'id');
        $this->safeRename('medicines', 'id_medicine', 'id');
        $this->safeRename('treatments', 'id_treatment', 'id');
        $this->safeRename('transactions', 'id_transaction', 'id');
        $this->safeRename('queues', 'id_queue', 'id');
        $this->safeRename('medical_records', 'id_medical_record', 'id');
        $this->safeRename('pets', 'id_pet', 'id');
        $this->safeRename('owners', 'id_owner', 'id');
        $this->safeRename('users', 'id_user', 'id');
    }
};
