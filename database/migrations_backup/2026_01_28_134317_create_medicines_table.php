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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obat'); // Nama obat
            $table->string('satuan')->default('tablet'); // tablet, botol, vial, dll
            $table->decimal('harga_jual', 10, 2); // Harga jual obat
            $table->integer('stok')->default(0); // Stok tersedia
            $table->string('dosis')->nullable(); // Contoh: 250mg, 500mg
            $table->text('keterangan')->nullable(); // Deskripsi obat
            $table->boolean('is_active')->default(true); // Obat masih aktif atau tidak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
