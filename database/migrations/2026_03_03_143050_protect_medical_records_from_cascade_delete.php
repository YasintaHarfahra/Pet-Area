<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Proteksi rekam medis dari cascade delete.
 *
 * Masalah sebelumnya:
 *   hapus owner → CASCADE hapus pets → CASCADE hapus medical_records
 *
 * Solusi:
 *   1. Soft deletes pada owners & pets (data tidak benar-benar hilang)
 *   2. FK pets→owners diubah ke SET NULL (bukan CASCADE)
 *   3. FK medical_records→pets diubah ke SET NULL (bukan CASCADE)
 *   4. FK queues→pets diubah ke SET NULL (bukan CASCADE)
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Soft deletes pada owners
        Schema::table('owners', function (Blueprint $table) {
            $table->softDeletes();
        });

        // 2. Soft deletes pada pets
        Schema::table('pets', function (Blueprint $table) {
            $table->softDeletes();
        });

        // 3. FK pets.id_owner: CASCADE → SET NULL
        Schema::table('pets', function (Blueprint $table) {
            $table->dropForeign('pets_id_owner_foreign');
            $table->unsignedBigInteger('id_owner')->nullable()->change();
            $table->foreign('id_owner')
                  ->references('id_owner')->on('owners')
                  ->onDelete('set null');
        });

        // 4. FK medical_records.id_pet: CASCADE → SET NULL
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropForeign('medical_records_id_pet_foreign');
            $table->foreign('id_pet')
                  ->references('id_pet')->on('pets')
                  ->onDelete('set null');
        });

        // 5. FK queues.id_pet: CASCADE → SET NULL
        Schema::table('queues', function (Blueprint $table) {
            $table->dropForeign('queues_id_pet_foreign');
            $table->foreign('id_pet')
                  ->references('id_pet')->on('pets')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->dropForeign(['id_pet']);
            $table->foreign('id_pet')
                  ->references('id_pet')->on('pets')
                  ->onDelete('cascade');
        });

        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropForeign(['id_pet']);
            $table->foreign('id_pet')
                  ->references('id_pet')->on('pets')
                  ->onDelete('cascade');
        });

        Schema::table('pets', function (Blueprint $table) {
            $table->dropForeign(['id_owner']);
            $table->unsignedBigInteger('id_owner')->nullable(false)->change();
            $table->foreign('id_owner')
                  ->references('id_owner')->on('owners')
                  ->onDelete('cascade');
        });

        Schema::table('pets', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('owners', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
