<?php

/**
 * =====================================================
 * MIGRATION: ADD PERFORMANCE INDEXES
 * =====================================================
 * 
 * Migration ini menambahkan INDEX pada kolom-kolom yang
 * sering digunakan untuk pencarian, filter, dan sorting.
 * 
 * INDEX mempercepat query SELECT dengan cara:
 * 1. Database tidak perlu scan seluruh tabel (Full Table Scan)
 * 2. Data diorganisir dalam struktur B-Tree yang efisien
 * 3. Pencarian O(log n) vs O(n) tanpa index
 * 
 * Contoh: 
 * - Tanpa index: 10.000 baris = 10.000 perbandingan
 * - Dengan index: 10.000 baris = ~14 perbandingan (log2 10000)
 * 
 * =====================================================
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==========================================
        // INDEX UNTUK TABEL MEDICAL_RECORDS
        // ==========================================
        Schema::table('medical_records', function (Blueprint $table) {
            // Index untuk filter berdasarkan tanggal pemeriksaan
            if (!$this->hasIndex('medical_records', 'medical_records_tgl_periksa_index')) {
                $table->index('tgl_periksa', 'medical_records_tgl_periksa_index');
            }
            
            // Index untuk filter berdasarkan dokter
            if (!$this->hasIndex('medical_records', 'medical_records_doctor_id_index')) {
                $table->index('doctor_id', 'medical_records_doctor_id_index');
            }
            
            // Composite index untuk pencarian umum (dokter + tanggal)
            if (!$this->hasIndex('medical_records', 'medical_records_doctor_date_index')) {
                $table->index(['doctor_id', 'tgl_periksa'], 'medical_records_doctor_date_index');
            }
        });

        // ==========================================
        // INDEX UNTUK TABEL TRANSACTIONS
        // ==========================================
        Schema::table('transactions', function (Blueprint $table) {
            // Index untuk filter status pembayaran
            if (!$this->hasIndex('transactions', 'transactions_status_bayar_index')) {
                $table->index('status_bayar', 'transactions_status_bayar_index');
            }
            
            // Index untuk filter tanggal transaksi
            if (!$this->hasIndex('transactions', 'transactions_created_at_index')) {
                $table->index('created_at', 'transactions_created_at_index');
            }
            
            // Composite index untuk laporan harian (status + tanggal)
            if (!$this->hasIndex('transactions', 'transactions_status_date_index')) {
                $table->index(['status_bayar', 'created_at'], 'transactions_status_date_index');
            }
        });

        // ==========================================
        // INDEX UNTUK TABEL PETS (tambahan)
        // ==========================================
        Schema::table('pets', function (Blueprint $table) {
            // Index untuk pencarian berdasarkan ras
            if (!$this->hasIndex('pets', 'pets_ras_index')) {
                $table->index('ras', 'pets_ras_index');
            }
            
            // Composite index untuk pencarian umum (jenis + nama)
            if (!$this->hasIndex('pets', 'pets_jenis_nama_index')) {
                $table->index(['jenis', 'nama_hewan'], 'pets_jenis_nama_index');
            }
        });

        // ==========================================
        // INDEX UNTUK TABEL OWNERS (tambahan)
        // ==========================================
        Schema::table('owners', function (Blueprint $table) {
            // Index untuk pencarian email
            if (!$this->hasIndex('owners', 'owners_email_index')) {
                $table->index('email', 'owners_email_index');
            }
            
            // Index untuk kode owner
            if (!$this->hasIndex('owners', 'owners_owner_code_index')) {
                $table->index('owner_code', 'owners_owner_code_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropIndex('medical_records_tgl_periksa_index');
            $table->dropIndex('medical_records_doctor_id_index');
            $table->dropIndex('medical_records_doctor_date_index');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('transactions_status_bayar_index');
            $table->dropIndex('transactions_created_at_index');
            $table->dropIndex('transactions_status_date_index');
        });

        Schema::table('pets', function (Blueprint $table) {
            $table->dropIndex('pets_ras_index');
            $table->dropIndex('pets_jenis_nama_index');
        });

        Schema::table('owners', function (Blueprint $table) {
            $table->dropIndex('owners_email_index');
            $table->dropIndex('owners_owner_code_index');
        });
    }

    /**
     * Cek apakah index sudah ada
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $indexes = Schema::getIndexes($table);
        foreach ($indexes as $index) {
            if ($index['name'] === $indexName) {
                return true;
            }
        }
        return false;
    }
};
