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
        // Jika tabel lama dengan nama singular ada, rename ke plural.
        if (Schema::hasTable('tingkat_perkembangan') && !Schema::hasTable('tingkat_perkembangans')) {
            Schema::rename('tingkat_perkembangan', 'tingkat_perkembangans');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tingkat_perkembangans') && !Schema::hasTable('tingkat_perkembangan')) {
            Schema::rename('tingkat_perkembangans', 'tingkat_perkembangan');
        }
    }
};
