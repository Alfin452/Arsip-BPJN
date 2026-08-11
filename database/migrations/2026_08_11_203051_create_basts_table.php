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
        Schema::create('basts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_pekerjaan_id')->constrained()->onDelete('cascade');
            $table->string('nomor_bast')->unique();
            $table->date('tanggal_bast');
            $table->string('nomor_penagihan')->nullable();
            $table->date('tanggal_penagihan')->nullable();
            $table->decimal('nilai_penagihan', 15, 2);
            $table->text('keterangan')->nullable();
            $table->string('file_dokumen')->nullable();
            $table->string('status')->default('Menunggu Verifikasi');
            
            // Audit trails
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basts');
    }
};
