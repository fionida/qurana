<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('tagline')->nullable();
            $table->boolean('butuh_seleksi_tes')->default(true);
            $table->boolean('butuh_kelulusan')->default(true);
            $table->boolean('butuh_sertifikat_resmi')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::table('gelombangs', function (Blueprint $table) {
            $table->foreignId('program_id')->nullable()->after('id')->constrained('programs')->nullOnDelete();
        });

        $now = now();
        $defaultId = DB::table('programs')->insertGetId([
            'nama' => 'Sertifikasi Guru Qurana (QFI)',
            'slug' => 'sertifikasi-guru-qurana',
            'deskripsi' => 'Program sertifikasi guru pendidik Al-Quran sesuai standar QFI.',
            'tagline' => 'Seleksi, tes, dan penerbitan sertifikat resmi',
            'butuh_seleksi_tes' => true,
            'butuh_kelulusan' => true,
            'butuh_sertifikat_resmi' => true,
            'is_active' => true,
            'urutan' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('gelombangs')->whereNull('program_id')->update(['program_id' => $defaultId]);
    }

    public function down(): void
    {
        Schema::table('gelombangs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('program_id');
        });

        Schema::dropIfExists('programs');
    }
};
