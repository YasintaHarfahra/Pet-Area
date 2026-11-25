<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            // Tambah pet_id untuk relasi dengan tabel pets (FK ditambahkan nanti)
            $table->unsignedBigInteger('pet_id')->nullable()->after('id');
            
            // Buat patient_id nullable (untuk backward compatibility)
            $table->unsignedBigInteger('patient_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->dropForeign(['pet_id']);
            $table->dropColumn('pet_id');
        });
    }
};
