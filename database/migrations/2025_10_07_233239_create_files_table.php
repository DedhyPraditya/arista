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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_name');
            $table->string('file_path');
            $table->bigInteger('file_size');
            $table->string('mime_type');
            $table->string('fileable_type')->nullable();
            $table->unsignedBigInteger('fileable_id')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();

            $table->index(['fileable_type', 'fileable_id']);
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
