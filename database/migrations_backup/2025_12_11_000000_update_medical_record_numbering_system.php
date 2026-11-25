<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update sistem penomeran rekam medis dari RM-YYYYMMDD-XXXXX
     * menjadi RM-XX-YYMM-NNNN dimana:
     * XX = Kode jenis hewan (CT, DJ, BR, dll)
     * YYMM = Tahun Bulan
     * NNNN = Nomor urut pasien bulan itu
     */
    public function up(): void
    {
        // Tambah kolom species_code di tabel patients
        if (!Schema::hasColumn('patients', 'species_code')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->string('species_code', 2)->nullable()->after('species')->comment('Kode jenis hewan (CT=Cat, DJ=Dog, BR=Bird, dll)');
            });
        }

        // Tambah kolom medical_record_number jika belum ada
        if (!Schema::hasColumn('patients', 'medical_record_number')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->string('medical_record_number')->nullable()->unique()->after('species_code')->comment('Format: RM-XX-YYMM-NNNN');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['species_code', 'medical_record_number']);
        });
    }
};
