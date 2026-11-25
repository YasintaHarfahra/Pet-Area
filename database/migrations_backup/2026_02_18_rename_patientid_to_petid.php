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
        // Rename patient_id to pet_id di medical_record_drafts
        if (Schema::hasColumn('medical_record_drafts', 'patient_id')) {
            Schema::table('medical_record_drafts', function (Blueprint $table) {
                $table->renameColumn('patient_id', 'pet_id');
            });
        }
        // Rename patient_id to pet_id di queues
        if (Schema::hasColumn('queues', 'patient_id')) {
            Schema::table('queues', function (Blueprint $table) {
                $table->renameColumn('patient_id', 'pet_id');
            });
        }
        // Rename patient_id to pet_id di transactions
        if (Schema::hasColumn('transactions', 'patient_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->renameColumn('patient_id', 'pet_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Balikkan perubahan jika perlu rollback
        if (Schema::hasColumn('medical_record_drafts', 'pet_id')) {
            Schema::table('medical_record_drafts', function (Blueprint $table) {
                $table->renameColumn('pet_id', 'patient_id');
            });
        }
        if (Schema::hasColumn('queues', 'pet_id')) {
            Schema::table('queues', function (Blueprint $table) {
                $table->renameColumn('pet_id', 'patient_id');
            });
        }
        if (Schema::hasColumn('transactions', 'pet_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->renameColumn('pet_id', 'patient_id');
            });
        }
    }
};
