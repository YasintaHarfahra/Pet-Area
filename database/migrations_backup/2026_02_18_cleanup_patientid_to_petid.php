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
        // 1. Copy data patient_id ke pet_id jika pet_id masih null
        if (Schema::hasColumn('medical_record_drafts', 'patient_id') && Schema::hasColumn('medical_record_drafts', 'pet_id')) {
            DB::statement('UPDATE medical_record_drafts SET pet_id = patient_id WHERE pet_id IS NULL AND patient_id IS NOT NULL');
        }
        if (Schema::hasColumn('queues', 'patient_id') && Schema::hasColumn('queues', 'pet_id')) {
            DB::statement('UPDATE queues SET pet_id = patient_id WHERE pet_id IS NULL AND patient_id IS NOT NULL');
        }
        if (Schema::hasColumn('transactions', 'patient_id') && Schema::hasColumn('transactions', 'pet_id')) {
            DB::statement('UPDATE transactions SET pet_id = patient_id WHERE pet_id IS NULL AND patient_id IS NOT NULL');
        }
        // 2. Drop kolom patient_id
        if (Schema::hasColumn('medical_record_drafts', 'patient_id')) {
            Schema::table('medical_record_drafts', function (Blueprint $table) {
                $table->dropColumn('patient_id');
            });
        }
        if (Schema::hasColumn('queues', 'patient_id')) {
            Schema::table('queues', function (Blueprint $table) {
                $table->dropColumn('patient_id');
            });
        }
        if (Schema::hasColumn('transactions', 'patient_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('patient_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tambahkan kembali kolom patient_id jika rollback
        if (!Schema::hasColumn('medical_record_drafts', 'patient_id')) {
            Schema::table('medical_record_drafts', function (Blueprint $table) {
                $table->unsignedBigInteger('patient_id')->nullable();
            });
        }
        if (!Schema::hasColumn('queues', 'patient_id')) {
            Schema::table('queues', function (Blueprint $table) {
                $table->unsignedBigInteger('patient_id')->nullable();
            });
        }
        if (!Schema::hasColumn('transactions', 'patient_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->unsignedBigInteger('patient_id')->nullable();
            });
        }
    }
};
