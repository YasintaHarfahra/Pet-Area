<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Buat tabel prescription_items untuk item resep obat
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('prescription_items')) {
            Schema::create('prescription_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('medical_record_id')->constrained('medical_records')->onDelete('cascade');
                $table->foreignId('medicine_id')->constrained('medicines')->onDelete('restrict');
                $table->integer('jumlah')->default(1);
                $table->decimal('harga_satuan', 12, 2)->default(0);
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->string('instruksi', 255)->nullable()->comment('Aturan pakai obat');
                $table->timestamps();
                
                $table->index('medical_record_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
