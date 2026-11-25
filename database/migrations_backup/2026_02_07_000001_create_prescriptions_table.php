<?php
/**
 * =====================================================
 * MIGRATION: CREATE PRESCRIPTIONS TABLE (RESEP OBAT)
 * =====================================================
 * 
 * Tabel untuk menyimpan detail resep obat dari rekam medis.
 * Setiap rekam medis bisa memiliki banyak item resep obat.
 * 
 * Relasi:
 * - Prescription belongsTo MedicalRecord/AnimalMedicalRecord
 * - Prescription belongsTo Medicine
 * =====================================================
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke rekam medis - support kedua tabel
            $table->foreignId('animal_medical_record_id')
                  ->nullable()
                  ->constrained('animal_medical_records')
                  ->onDelete('cascade');
            
            // Support rekam medis lama (patients system)
            $table->foreignId('medical_record_id')
                  ->nullable()
                  ->constrained('medical_records')
                  ->onDelete('cascade');
            
            // Foreign key ke obat
            $table->foreignId('medicine_id')
                  ->constrained('medicines')
                  ->onDelete('restrict'); // Tidak bisa hapus obat yang sudah diresepkan
            
            // Jumlah obat yang diresepkan
            $table->integer('jumlah')->default(1);
            
            // Harga satuan saat resep dibuat (untuk historis)
            $table->decimal('harga_satuan', 10, 2)->default(0);
            
            // Subtotal (jumlah x harga_satuan)
            $table->decimal('subtotal', 10, 2)->default(0);
            
            // Instruksi penggunaan obat
            $table->text('instruksi')->nullable();
            
            // Catatan tambahan
            $table->text('catatan')->nullable();
            
            $table->timestamps();
            
            // Index untuk mempercepat query
            $table->index(['animal_medical_record_id']);
            $table->index(['medical_record_id']);
            $table->index(['medicine_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
