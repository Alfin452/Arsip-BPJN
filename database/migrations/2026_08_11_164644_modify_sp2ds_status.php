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
        Schema::table('sp2ds', function (Blueprint $table) {
            // Drop enum old
            $table->dropColumn('status');
        });
        
        Schema::table('sp2ds', function (Blueprint $table) {
            // Create string new
            $table->string('status')->default('Draft')->after('file_pdf');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sp2ds', function (Blueprint $table) {
            $table->dropColumn(['status', 'verified_at']);
        });

        Schema::table('sp2ds', function (Blueprint $table) {
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->after('file_pdf');
        });
    }
};
