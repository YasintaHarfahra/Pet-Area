<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tindakan')->comment('Nama tindakan/layanan medis'); // Contoh: Vaksin, Operasi, Pemeriksaan Umum
            $table->text('deskripsi')->nullable()->comment('Deskripsi detail tindakan');
            $table->decimal('harga', 12, 2)->comment('Harga dalam Rupiah'); // Contoh: 150000.00
            $table->string('kategori')->nullable()->comment('Kategori tindakan: vaksin, operasi, konsultasi, dll');
            $table->boolean('is_active')->default(true)->comment('Apakah tindakan masih aktif');
            $table->timestamps();

            $table->index('kategori');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
