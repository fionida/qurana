<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 32)->unique();
            $table->enum('tipe', ['persen', 'nominal'])->default('nominal');
            $table->unsignedInteger('nilai');
            $table->foreignId('gelombang_id')->nullable()->constrained('gelombangs')->nullOnDelete();
            $table->unsignedInteger('maks_pakai')->nullable();
            $table->unsignedInteger('terpakai')->default(0);
            $table->date('berlaku_sampai')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('santris', function (Blueprint $table) {
            $table->foreignId('voucher_id')->nullable()->after('status_pembayaran')->constrained('vouchers')->nullOnDelete();
            $table->unsignedInteger('jumlah_bayar')->nullable()->after('voucher_id');
        });

        Schema::create('pembayaran_riwayat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->unsignedInteger('nominal_dasar');
            $table->unsignedInteger('diskon')->default(0);
            $table->unsignedInteger('jumlah_bayar');
            $table->enum('metode', ['transfer', 'bayar_ditempat']);
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('log_status_pendaftar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->string('status_dari', 40)->nullable();
            $table->string('status_ke', 40);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('santri_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->string('jenis', 40);
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santri_dokumen');
        Schema::dropIfExists('log_status_pendaftar');
        Schema::dropIfExists('pembayaran_riwayat');

        Schema::table('santris', function (Blueprint $table) {
            $table->dropConstrainedForeignId('voucher_id');
            $table->dropColumn('jumlah_bayar');
        });

        Schema::dropIfExists('vouchers');
    }
};
