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
        Schema::create('lokasi_arsips', function (Blueprint $table) {
            $table->id();
            $table->string('ruangan');
            $table->string('gedung');
            $table->string('lemari');
            $table->string('rak');
            $table->string('book');
            $table->string('folder');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_arsips');
    }
};
