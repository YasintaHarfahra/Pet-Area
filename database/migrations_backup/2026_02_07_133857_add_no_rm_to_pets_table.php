<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migrasi: Menambahkan No. Rekam Medis (no_rm) ke tabel pets
 * Format: RM0001, RM0002, dst (sederhana dan mudah diingat)
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->string('no_rm', 10)->nullable()->unique()->after('id')
                  ->comment('Nomor Rekam Medis format: RM0001');
        });

        // Generate no_rm untuk data yang sudah ada
        $pets = DB::table('pets')->whereNull('no_rm')->orderBy('id')->get();
        foreach ($pets as $index => $pet) {
            $number = str_pad($pet->id, 4, '0', STR_PAD_LEFT);
            DB::table('pets')->where('id', $pet->id)->update([
                'no_rm' => 'RM' . $number
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn('no_rm');
        });
    }
};
