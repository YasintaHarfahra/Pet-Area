<?php
/**
 * =====================================================
 * MIGRATION: CREATE OWNERS TABLE
 * =====================================================
 * 
 * Tabel ini menyimpan data PEMILIK HEWAN sebagai entitas utama.
 * Semua hewan akan terhubung ke pemilik melalui foreign key.
 * 
 * Tujuan: Memudahkan pencarian berdasarkan pemilik,
 * lalu menampilkan semua hewan milik pemilik tersebut.
 * 
 * Relasi: Owner hasMany Animal
 * =====================================================
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel owners untuk menyimpan data pemilik hewan
     */
    public function up(): void
    {
        Schema::create('owners', function (Blueprint $table) {
            // Primary key - ID unik untuk setiap pemilik
            $table->id();
            
            // Kode unik pemilik (format: OWN-YYMM-XXXX)
            // Berguna untuk referensi cepat dan pencarian
            $table->string('owner_code')->unique()->nullable();
            
            // Data identitas pemilik
            $table->string('nama');                          // Nama lengkap pemilik
            $table->text('alamat')->nullable();              // Alamat lengkap
            $table->string('no_hp', 20);                     // Nomor HP (untuk pencarian & kontak)
            $table->string('email')->nullable();             // Email (opsional)
            
            // Data tambahan (opsional)
            $table->string('no_ktp', 20)->nullable();        // No KTP untuk identifikasi
            $table->text('catatan')->nullable();             // Catatan khusus tentang pemilik
            
            // Timestamp otomatis Laravel
            $table->timestamps();                            // created_at & updated_at
            
            // Index untuk mempercepat pencarian
            $table->index('nama');                           // Index nama untuk search
            $table->index('no_hp');                          // Index no_hp untuk search
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel owners jika rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
