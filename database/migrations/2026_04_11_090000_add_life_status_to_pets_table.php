<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->enum('status_hidup', ['hidup', 'mati'])
                ->default('hidup')
                ->after('catatan');
            $table->date('tanggal_kematian')->nullable()->after('status_hidup');
            $table->text('penyebab_kematian')->nullable()->after('tanggal_kematian');

            $table->index('status_hidup');
            $table->index('tanggal_kematian');
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropIndex(['status_hidup']);
            $table->dropIndex(['tanggal_kematian']);
            $table->dropColumn(['status_hidup', 'tanggal_kematian', 'penyebab_kematian']);
        });
    }
};
