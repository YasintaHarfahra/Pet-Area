<?php
/**
 * =====================================================
 * MIGRATION: CREATE REFERRALS TABLE (SURAT RUJUKAN)
 * =====================================================
 * 
 * Tabel untuk menyimpan surat rujukan dari dokter.
 * Dokter membuat rujukan, Admin yang mencetak.
 * 
 * Relasi:
 * - Referral belongsTo MedicalRecord/AnimalMedicalRecord
 * - Referral belongsTo User (dokter yang membuat)
 * =====================================================
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            
            // Nomor surat rujukan (auto-generate)
            $table->string('nomor_rujukan')->unique()->nullable();
            
            // Foreign key ke rekam medis
            $table->foreignId('animal_medical_record_id')
                  ->nullable()
                  ->constrained('animal_medical_records')
                  ->onDelete('cascade');
            
            // Support rekam medis lama
            $table->foreignId('medical_record_id')
                  ->nullable()
                  ->constrained('medical_records')
                  ->onDelete('cascade');
            
            // Dokter yang membuat rujukan
            $table->foreignId('doctor_id')
                  ->constrained('users')
                  ->onDelete('restrict');
            
            // Tujuan rujukan (klinik/rumah sakit tujuan)
            $table->string('tujuan_rujukan');
            
            // Alasan/isi rujukan
            $table->text('alasan_rujukan')->nullable();
            
            // Diagnosa sementara
            $table->text('diagnosa_sementara')->nullable();
            
            // Tindakan yang sudah dilakukan
            $table->text('tindakan_dilakukan')->nullable();
            
            // Tanggal rujukan
            $table->date('tanggal_rujukan');
            
            // Status cetak: pending (belum dicetak), printed (sudah dicetak)
            $table->enum('status_cetak', ['pending', 'printed'])->default('pending');
            
            // Tanggal dicetak
            $table->timestamp('printed_at')->nullable();
            
            // Admin yang mencetak
            $table->foreignId('printed_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            // Catatan tambahan
            $table->text('catatan')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index(['animal_medical_record_id']);
            $table->index(['medical_record_id']);
            $table->index(['status_cetak']);
            $table->index(['tanggal_rujukan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
