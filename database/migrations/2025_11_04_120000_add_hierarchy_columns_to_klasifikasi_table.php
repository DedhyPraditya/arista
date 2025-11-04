<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Tambah kolom untuk mendukung struktur hierarki klasifikasi.
     * parent_kode : menunjuk kode induk (null untuk root)
     * level       : tingkat kedalaman (root = 0)
     * is_leaf     : menandai apakah node akhir (memiliki retensi yang relevan)
     */
    public function up(): void
    {
        Schema::table('klasifikasi', function (Blueprint $table) {
            // Pastikan tidak menabrak nama kolom yang sudah ada
            if (!Schema::hasColumn('klasifikasi', 'parent_kode')) {
                $table->string('parent_kode')->nullable()->after('kode');
                $table->index('parent_kode', 'klasifikasi_parent_kode_index');
            }
            if (!Schema::hasColumn('klasifikasi', 'level')) {
                $table->unsignedTinyInteger('level')->default(0)->after('parent_kode');
                $table->index(['parent_kode', 'level'], 'klasifikasi_parent_level_index');
            }
            if (!Schema::hasColumn('klasifikasi', 'is_leaf')) {
                $table->boolean('is_leaf')->default(false)->after('level');
            }
        });
    }

    /**
     * Rollback kolom hierarki.
     */
    public function down(): void
    {
        Schema::table('klasifikasi', function (Blueprint $table) {
            if (Schema::hasColumn('klasifikasi', 'is_leaf')) {
                $table->dropColumn('is_leaf');
            }
            if (Schema::hasColumn('klasifikasi', 'level')) {
                // indeks gabungan akan ikut terhapus ketika kolom di-drop
                $table->dropColumn('level');
            }
            if (Schema::hasColumn('klasifikasi', 'parent_kode')) {
                $table->dropIndex('klasifikasi_parent_kode_index');
                $table->dropIndex('klasifikasi_parent_level_index');
                $table->dropColumn('parent_kode');
            }
        });
    }
};
