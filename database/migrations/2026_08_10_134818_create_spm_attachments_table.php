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
        Schema::create('spm_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spm_id')->constrained()->cascadeOnDelete();
            $table->string('tipe_file'); // spm, kuitansi, surat_tugas, laporan, dokumentasi
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spm_attachments');
    }
};
