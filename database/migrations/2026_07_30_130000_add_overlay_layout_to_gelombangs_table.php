<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gelombangs', function (Blueprint $table) {
            $table->json('overlay_layout')->nullable()->after('template_siap_cetak');
        });
    }

    public function down(): void
    {
        Schema::table('gelombangs', function (Blueprint $table) {
            $table->dropColumn('overlay_layout');
        });
    }
};
