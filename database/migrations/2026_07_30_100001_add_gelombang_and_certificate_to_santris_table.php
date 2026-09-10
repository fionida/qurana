<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->foreignId('gelombang_id')->nullable()->after('id')->constrained('gelombangs')->nullOnDelete();
            $table->unsignedInteger('nomor_sertifikat_urut')->nullable()->after('nomor_pendaftaran');
            $table->string('niq', 32)->nullable()->after('nomor_sertifikat_urut');
            $table->string('nomor_sertifikat', 64)->nullable()->after('niq');
        });
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropConstrainedForeignId('gelombang_id');
            $table->dropColumn(['nomor_sertifikat_urut', 'niq', 'nomor_sertifikat']);
        });
    }
};
