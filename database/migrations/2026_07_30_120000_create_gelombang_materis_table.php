<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gelombang_materis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gelombang_id')->constrained('gelombangs')->cascadeOnDelete();
            $table->unsignedTinyInteger('urutan');
            $table->string('nama_materi');
            $table->string('durasi', 20)->nullable();
            $table->unsignedSmallInteger('jpl')->nullable();
            $table->timestamps();

            $table->unique(['gelombang_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gelombang_materis');
    }
};
