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
        Schema::create('paket_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('satker_id')->constrained('satkers')->onDelete('cascade');
            $table->foreignId('ppk_id')->constrained('ppks')->onDelete('cascade');
            $table->foreignId('penyedia_id')->constrained('penyedias')->onDelete('cascade');
            $table->string('nama_paket');
            $table->string('nomor_kontrak');
            $table->date('tanggal_kontrak');
            $table->decimal('nilai_kontrak', 20, 2);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_pekerjaans');
    }
};
