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
        Schema::table('whats_app_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('whats_app_logs', 'nama_hewan')) {
                $table->string('nama_hewan')->nullable()->after('nama_pemilik');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whats_app_logs', function (Blueprint $table) {
            if (Schema::hasColumn('whats_app_logs', 'nama_hewan')) {
                $table->dropColumn('nama_hewan');
            }
        });
    }
};
