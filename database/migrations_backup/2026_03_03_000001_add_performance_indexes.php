<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah index pada kolom FK yang sering di-query
 * Mempercepat JOIN, WHERE, dan ORDER BY pada database remote
 */
return new class extends Migration
{
    private function safeIndex(string $table, string $column): void
    {
        $indexName = "{$table}_{$column}_index";
        // Cross-DB compatible: use Schema Builder with try-catch
        try {
            Schema::table($table, function (Blueprint $t) use ($column, $indexName) {
                $t->index($column, $indexName);
            });
        } catch (\Exception $e) {
            // Skip if index already exists
        }
    }

    public function up(): void
    {
        // medical_records - paling sering di-query
        $this->safeIndex('medical_records', 'id_pet');
        $this->safeIndex('medical_records', 'id_doctor');
        $this->safeIndex('medical_records', 'id_queue');
        $this->safeIndex('medical_records', 'visit_date');
        $this->safeIndex('medical_records', 'next_control_date');

        // queues
        $this->safeIndex('queues', 'id_pet');
        $this->safeIndex('queues', 'id_doctor');
        $this->safeIndex('queues', 'queue_date');
        $this->safeIndex('queues', 'status');

        // transactions
        $this->safeIndex('transactions', 'id_medical_record');
        $this->safeIndex('transactions', 'id_admin');
        $this->safeIndex('transactions', 'id_doctor');
        $this->safeIndex('transactions', 'status_bayar');
        $this->safeIndex('transactions', 'tgl_bayar');

        // pets
        $this->safeIndex('pets', 'id_owner');

        // prescription_items
        $this->safeIndex('prescription_items', 'id_medical_record');
        $this->safeIndex('prescription_items', 'id_medicine');

        // referrals
        $this->safeIndex('referrals', 'id_medical_record');
        $this->safeIndex('referrals', 'id_doctor');
        $this->safeIndex('referrals', 'status_cetak');

        // medical_record_drafts
        $this->safeIndex('medical_record_drafts', 'id_queue');
        $this->safeIndex('medical_record_drafts', 'id_doctor');

        // medical_record_treatments
        $this->safeIndex('medical_record_treatments', 'id_medical_record');
        $this->safeIndex('medical_record_treatments', 'id_treatment');

        // medicines
        $this->safeIndex('medicines', 'stok');
    }

    public function down(): void
    {
        $indexes = [
            'medical_records' => ['id_pet', 'id_doctor', 'id_queue', 'visit_date', 'next_control_date'],
            'queues' => ['id_pet', 'id_doctor', 'queue_date', 'status'],
            'transactions' => ['id_medical_record', 'id_admin', 'id_doctor', 'status_bayar', 'tgl_bayar'],
            'pets' => ['id_owner'],
            'prescription_items' => ['id_medical_record', 'id_medicine'],
            'referrals' => ['id_medical_record', 'id_doctor', 'status_cetak'],
            'medical_record_drafts' => ['id_queue', 'id_doctor'],
            'medical_record_treatments' => ['id_medical_record', 'id_treatment'],
            'medicines' => ['stok'],
        ];

        foreach ($indexes as $table => $columns) {
            foreach ($columns as $column) {
                try {
                    Schema::table($table, function (Blueprint $t) use ($table, $column) {
                        $t->dropIndex("{$table}_{$column}_index");
                    });
                } catch (\Exception $e) {
                    // Index doesn't exist, skip
                }
            }
        }
    }
};
