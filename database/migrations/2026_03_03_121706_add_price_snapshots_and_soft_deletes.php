<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 1. Tambah kolom harga pada pivot medical_record_treatments (snapshot harga tindakan saat pemeriksaan)
     * 2. Tambah soft deletes pada medicines dan treatments agar data historis tidak rusak saat dihapus
     * 3. Backfill harga tindakan dari tabel treatments untuk data lama
     */
    public function up(): void
    {
        // 1. Tambah kolom harga snapshot di pivot medical_record_treatments
        Schema::table('medical_record_treatments', function (Blueprint $table) {
            $table->decimal('harga', 12, 2)->default(0)->after('id_treatment')
                  ->comment('Snapshot harga tindakan saat pemeriksaan');
        });

        // 2. Soft deletes untuk medicines
        Schema::table('medicines', function (Blueprint $table) {
            $table->softDeletes();
        });

        // 3. Soft deletes untuk treatments
        Schema::table('treatments', function (Blueprint $table) {
            $table->softDeletes();
        });

        // 4. Backfill: isi harga snapshot dari tabel treatments untuk data lama
        DB::statement("
            UPDATE medical_record_treatments mrt
            INNER JOIN treatments t ON mrt.id_treatment = t.id_treatment
            SET mrt.harga = t.harga
            WHERE mrt.harga = 0
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_record_treatments', function (Blueprint $table) {
            $table->dropColumn('harga');
        });

        Schema::table('medicines', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('treatments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
