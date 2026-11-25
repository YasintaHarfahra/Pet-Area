<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Hapus tabel-tabel yang sudah tidak digunakan (redundan).
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // Drop tabel-tabel redundan
        Schema::dropIfExists('animals');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('animal_medical_records');
        Schema::dropIfExists('visits');
        Schema::dropIfExists('prescriptions');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Tidak ada restore untuk struktur lama yang salah.
     */
    public function down(): void
    {
        // Tidak perlu restore tabel-tabel redundan.
    }
};
