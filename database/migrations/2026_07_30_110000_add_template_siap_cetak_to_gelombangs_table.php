<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gelombangs', function (Blueprint $table) {
            $table->boolean('template_siap_cetak')->default(true)->after('template_halaman_2');
        });
    }

    public function down(): void
    {
        Schema::table('gelombangs', function (Blueprint $table) {
            $table->dropColumn('template_siap_cetak');
        });
    }
};
