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
        Schema::table('spms', function (Blueprint $table) {
            $table->dropColumn('file_pdf');
            $table->string('tahun_anggaran', 4)->nullable()->after('nomor_spm');
            $table->string('jenis_spm')->nullable()->after('tahun_anggaran'); // UP, TUP, GUP, dll
            $table->foreignId('satker_id')->nullable()->constrained('satkers')->nullOnDelete()->after('jenis_spm');
            $table->foreignId('ppk_id')->nullable()->constrained('ppks')->nullOnDelete()->after('satker_id');
            $table->text('uraian')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spms', function (Blueprint $table) {
            $table->string('file_pdf')->nullable();
            $table->dropForeign(['satker_id']);
            $table->dropForeign(['ppk_id']);
            $table->dropColumn(['tahun_anggaran', 'jenis_spm', 'satker_id', 'ppk_id', 'uraian']);
        });
    }
};
