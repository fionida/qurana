<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gelombangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_registration_open')->default(false);
            $table->date('tanggal_sertifikasi_mulai');
            $table->date('tanggal_sertifikasi_selesai');
            $table->date('tanggal_terbit_masehi');
            $table->string('tanggal_terbit_hijriyah');
            $table->string('kota_terbit')->default('Malang');
            $table->string('kode_batch', 20)->default('35.73');
            $table->string('jenis_nomor', 20)->default('S.S');
            $table->unsignedInteger('nomor_urut_berikutnya')->default(1);
            $table->string('template_halaman_1')->nullable();
            $table->string('template_halaman_2')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gelombangs');
    }
};
