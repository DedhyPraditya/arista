<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisDokumensTable extends Migration
{
    public function up()
    {
        Schema::create('jenis_dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_dokumen')->unique(); // Nama jenis dokumen
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_dokumens');
    }
}
