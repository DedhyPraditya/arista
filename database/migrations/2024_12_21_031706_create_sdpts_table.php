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
        Schema::create('sdpts', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->integer('tahun_surat');
            $table->string('pencipta_arsip');
            $table->foreignId('unit_pengelola_id')->constrained('unit_pengelolas')->onDelete('cascade');
            $table->foreignId('kode_klasifikasi_id')->constrained('klasifikasi')->onDelete('cascade');
            $table->string('prihal');
            $table->text('uraian_informasi');
            $table->foreignId('tingkat_perkembangan_id')->constrained('tingkat_perkembangan')->onDelete('cascade');
            $table->foreignId('lokasi_arsip_id')->constrained('lokasi_arsips')->onDelete('cascade');
            $table->integer('jumlah_item');
            $table->string('lampiran')->nullable();
            $table->integer('retensi');
            $table->text('keterangan')->nullable();
            $table->foreignId('nasib_akhir_id')->constrained('nasib_akhir')->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdpts');
    }
};
