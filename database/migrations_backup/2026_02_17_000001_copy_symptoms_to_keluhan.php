<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Salin data symptoms ke keluhan jika keluhan masih kosong
        DB::statement("UPDATE medical_records SET keluhan = symptoms WHERE (keluhan IS NULL OR keluhan = '') AND symptoms IS NOT NULL AND symptoms != ''");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback, biarkan data tetap
    }
};
