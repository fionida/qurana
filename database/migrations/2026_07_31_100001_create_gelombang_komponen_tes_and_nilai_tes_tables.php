<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gelombang_komponen_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gelombang_id')->constrained('gelombangs')->cascadeOnDelete();
            $table->unsignedTinyInteger('urutan');
            $table->string('nama_komponen');
            $table->decimal('bobot', 5, 2)->nullable();
            $table->decimal('nilai_maksimal', 5, 2)->default(100);
            $table->timestamps();

            $table->unique(['gelombang_id', 'urutan']);
        });

        Schema::create('nilai_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->foreignId('gelombang_komponen_tes_id')->constrained('gelombang_komponen_tes')->cascadeOnDelete();
            $table->decimal('nilai', 5, 2);
            $table->text('catatan')->nullable();
            $table->foreignId('diinput_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['santri_id', 'gelombang_komponen_tes_id'], 'nilai_tes_santri_komponen_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_tes');
        Schema::dropIfExists('gelombang_komponen_tes');
    }
};
