<?php
/**
 * =====================================================
 * MIGRATION: UPDATE TRANSACTIONS TABLE
 * =====================================================
 * 
 * Menambahkan kolom untuk relasi ke rekam medis dan
 * status pembayaran yang lebih detail.
 * =====================================================
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Foreign key ke rekam medis hewan (sistem baru)
            if (!Schema::hasColumn('transactions', 'animal_medical_record_id')) {
                $table->foreignId('animal_medical_record_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('animal_medical_records')
                      ->onDelete('set null');
            }
            
            // Status bayar yang lebih jelas
            if (!Schema::hasColumn('transactions', 'status_bayar')) {
                $table->enum('status_bayar', ['unpaid', 'paid', 'partial'])
                      ->default('unpaid')
                      ->after('amount');
            }
            
            // Biaya pemeriksaan
            if (!Schema::hasColumn('transactions', 'biaya_pemeriksaan')) {
                $table->decimal('biaya_pemeriksaan', 10, 2)->default(0)->after('amount');
            }
            
            // Biaya tindakan
            if (!Schema::hasColumn('transactions', 'biaya_tindakan')) {
                $table->decimal('biaya_tindakan', 10, 2)->default(0)->after('biaya_pemeriksaan');
            }
            
            // Biaya obat
            if (!Schema::hasColumn('transactions', 'biaya_obat')) {
                $table->decimal('biaya_obat', 10, 2)->default(0)->after('biaya_tindakan');
            }
            
            // Tanggal bayar
            if (!Schema::hasColumn('transactions', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('status_bayar');
            }
            
            // Kasir yang memproses pembayaran
            if (!Schema::hasColumn('transactions', 'cashier_id')) {
                $table->foreignId('cashier_id')
                      ->nullable()
                      ->after('admin_id')
                      ->constrained('users')
                      ->onDelete('set null');
            }
            
            // Index
            $table->index(['animal_medical_record_id']);
            $table->index(['status_bayar']);
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['animal_medical_record_id']);
            $table->dropForeign(['cashier_id']);
            $table->dropColumn([
                'animal_medical_record_id',
                'status_bayar',
                'biaya_pemeriksaan',
                'biaya_tindakan',
                'biaya_obat',
                'paid_at',
                'cashier_id'
            ]);
        });
    }
};
