<?php
// database/migrations/YYYY_MM_DD_HHMMSS_add_medical_record_number_to_patients_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Tambahkan kolom medical_record_number setelah kolom 'id'
            $table->string('medical_record_number')->unique()->nullable()->after('id');
            // Unique agar tidak ada nomor RM yang sama
            // Nullable karena pasien yang sudah ada sebelum migrasi ini tidak punya nomor RM
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('medical_record_number');
        });
    }
};