<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('santris', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran')->unique();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('lembaga');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_wa')->nullable();
            $table->string('email')->nullable();
            $table->string('pas_foto');
            $table->enum('metode_pembayaran', ['transfer', 'bayar_ditempat']);
            $table->string('bukti_transfer')->nullable();
            $table->enum('status_pembayaran', ['pending', 'lunas'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};
