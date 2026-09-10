<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penandatangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan')->default(1);
            $table->string('nama');
            $table->string('jabatan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['program_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penandatangans');
    }
};
