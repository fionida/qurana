<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->string('provinsi_id', 2)->nullable()->after('alamat');
            $table->string('provinsi')->nullable()->after('provinsi_id');
            $table->string('kota_kab_id', 4)->nullable()->after('provinsi');
            $table->string('kota_kab')->nullable()->after('kota_kab_id');
            $table->string('kecamatan_id', 6)->nullable()->after('kota_kab');
            $table->string('kecamatan')->nullable()->after('kecamatan_id');
            $table->string('desa_id', 10)->nullable()->after('kecamatan');
            $table->string('desa')->nullable()->after('desa_id');
        });
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropColumn([
                'provinsi_id', 'provinsi',
                'kota_kab_id', 'kota_kab',
                'kecamatan_id', 'kecamatan',
                'desa_id', 'desa',
            ]);
        });
    }
};
