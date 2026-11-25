<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Update medical_records table untuk skema baru
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            // Tambah kolom baru jika belum ada
            if (!Schema::hasColumn('medical_records', 'pet_id')) {
                $table->foreignId('pet_id')->nullable()->after('id');
            }
            
            if (!Schema::hasColumn('medical_records', 'nomor_rekam_medis')) {
                $table->string('nomor_rekam_medis', 30)->nullable()->after('id');
            }
            
            if (!Schema::hasColumn('medical_records', 'tgl_periksa')) {
                $table->date('tgl_periksa')->nullable()->after('doctor_id');
            }
            
            if (!Schema::hasColumn('medical_records', 'berat_badan')) {
                $table->decimal('berat_badan', 5, 2)->nullable();
            }
            
            if (!Schema::hasColumn('medical_records', 'suhu_tubuh')) {
                $table->decimal('suhu_tubuh', 4, 1)->nullable();
            }
            
            if (!Schema::hasColumn('medical_records', 'keluhan')) {
                $table->text('keluhan')->nullable();
            }
            
            if (!Schema::hasColumn('medical_records', 'diagnosa')) {
                $table->text('diagnosa')->nullable();
            }
            
            if (!Schema::hasColumn('medical_records', 'tindakan')) {
                $table->text('tindakan')->nullable();
            }
            
            if (!Schema::hasColumn('medical_records', 'tgl_kontrol_berikutnya')) {
                $table->date('tgl_kontrol_berikutnya')->nullable();
            }
            
            if (!Schema::hasColumn('medical_records', 'catatan_kontrol')) {
                $table->text('catatan_kontrol')->nullable();
            }
            
            if (!Schema::hasColumn('medical_records', 'biaya_pemeriksaan')) {
                $table->decimal('biaya_pemeriksaan', 12, 2)->default(0);
            }
            
            if (!Schema::hasColumn('medical_records', 'biaya_tindakan')) {
                $table->decimal('biaya_tindakan', 12, 2)->default(0);
            }
            
            if (!Schema::hasColumn('medical_records', 'biaya_obat')) {
                $table->decimal('biaya_obat', 12, 2)->default(0);
            }
            
            if (!Schema::hasColumn('medical_records', 'total_biaya')) {
                $table->decimal('total_biaya', 12, 2)->default(0);
            }
            
            if (!Schema::hasColumn('medical_records', 'status')) {
                $table->enum('status', ['draft', 'completed'])->default('completed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            // Drop kolom yang ditambahkan
            $columns = [
                'pet_id', 'nomor_rekam_medis', 'tgl_periksa', 'berat_badan',
                'suhu_tubuh', 'keluhan', 'diagnosa', 'tindakan',
                'tgl_kontrol_berikutnya', 'catatan_kontrol',
                'biaya_pemeriksaan', 'biaya_tindakan', 'biaya_obat',
                'total_biaya', 'status'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('medical_records', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
