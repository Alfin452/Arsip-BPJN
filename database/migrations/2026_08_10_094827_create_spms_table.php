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
        Schema::create('spms', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_spm')->unique();
            $table->date('tanggal_spm');
            $table->decimal('nilai_spm', 15, 2);
            $table->string('jenis_tagihan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('file_pdf');
            $table->string('status')->default('Draft');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spms');
    }
};
