<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Buat tabel pets sebagai pengganti animals
 */
return new class extends Migration
{
    public function up(): void
    {
        // Cek jika tabel pets belum ada
        if (!Schema::hasTable('pets')) {
            Schema::create('pets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('owner_id')->constrained('owners')->onDelete('cascade');
                $table->string('nama_hewan', 100);
                $table->string('jenis', 50)->comment('Kucing, Anjing, dll');
                $table->string('ras', 100)->nullable();
                $table->date('tgl_lahir')->nullable();
                $table->enum('jenis_kelamin', ['jantan', 'betina'])->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
                
                $table->index('nama_hewan');
                $table->index('jenis');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
