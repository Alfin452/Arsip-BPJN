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
        Schema::create('sp2ds', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sp2d')->unique();
            $table->date('tanggal_sp2d');
            $table->decimal('nilai_sp2d', 15, 2);
            $table->foreignId('spm_id')->nullable()->constrained('spms')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->string('file_pdf');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
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
        Schema::dropIfExists('sp2ds');
    }
};
