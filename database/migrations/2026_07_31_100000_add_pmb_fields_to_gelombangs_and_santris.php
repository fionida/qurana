<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gelombangs', function (Blueprint $table) {
            $table->unsignedInteger('biaya_pendaftaran')->nullable()->after('is_registration_open');
            $table->unsignedInteger('kuota')->nullable()->after('biaya_pendaftaran');
            $table->date('pendaftaran_buka')->nullable()->after('kuota');
            $table->date('pendaftaran_tutup')->nullable()->after('pendaftaran_buka');
            $table->date('jadwal_tes_mulai')->nullable()->after('pendaftaran_tutup');
            $table->date('jadwal_tes_selesai')->nullable()->after('jadwal_tes_mulai');
            $table->string('lokasi_tes')->nullable()->after('jadwal_tes_selesai');
            $table->decimal('nilai_lulus_minimal', 5, 2)->nullable()->after('lokasi_tes');
        });

        Schema::table('santris', function (Blueprint $table) {
            $table->enum('status_pendaftar', [
                'terdaftar',
                'menunggu_pembayaran',
                'lunas',
                'mengikuti_tes',
                'lulus',
                'tidak_lulus',
                'sertifikat_diterbitkan',
            ])->default('terdaftar')->after('status_pembayaran');

            $table->enum('status_kelulusan', [
                'belum_tes',
                'lulus',
                'tidak_lulus',
            ])->default('belum_tes')->after('status_pendaftar');

            $table->decimal('nilai_akhir', 5, 2)->nullable()->after('status_kelulusan');
        });

        foreach (DB::table('santris')->orderBy('id')->get() as $row) {
            $statusPendaftar = 'terdaftar';

            if ($row->status_pembayaran === 'pending') {
                $statusPendaftar = 'menunggu_pembayaran';
            } elseif ($row->status_pembayaran === 'lunas') {
                $statusPendaftar = ! empty($row->nomor_sertifikat)
                    ? 'sertifikat_diterbitkan'
                    : 'lunas';
            }

            DB::table('santris')->where('id', $row->id)->update([
                'status_pendaftar' => $statusPendaftar,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropColumn(['status_pendaftar', 'status_kelulusan', 'nilai_akhir']);
        });

        Schema::table('gelombangs', function (Blueprint $table) {
            $table->dropColumn([
                'biaya_pendaftaran',
                'kuota',
                'pendaftaran_buka',
                'pendaftaran_tutup',
                'jadwal_tes_mulai',
                'jadwal_tes_selesai',
                'lokasi_tes',
                'nilai_lulus_minimal',
            ]);
        });
    }
};
