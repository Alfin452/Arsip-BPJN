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
        Schema::create('dipas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('satker_id')->constrained('satkers')->onDelete('cascade');
            $table->year('tahun_anggaran');
            $table->string('nomor_dipa');
            $table->date('tanggal_dipa');
            $table->decimal('nilai_pagu', 20, 2);
            $table->timestamps();

            // Memastikan satu satker hanya memiliki satu DIPA per tahun anggaran
            $table->unique(['satker_id', 'tahun_anggaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dipas');
    }
};
