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
    // Menggunakan nama tabel plural yang konsisten dengan model & validasi
    Schema::create('tingkat_perkembangans', function (Blueprint $table) {
            $table->id();
            $table->string('tingkat_perkembangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::dropIfExists('tingkat_perkembangans');
    }
};
