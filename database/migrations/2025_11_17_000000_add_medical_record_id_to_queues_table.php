<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            // Tambah kolom medical_record_id untuk link ke rekam medis
            $table->foreignId('medical_record_id')->nullable()->after('complaint')->constrained('medical_records')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->dropForeignIdFor('medical_records');
            $table->dropColumn('medical_record_id');
        });
    }
};
