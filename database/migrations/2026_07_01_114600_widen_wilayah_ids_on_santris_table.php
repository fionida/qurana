<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE santris
            MODIFY provinsi_id VARCHAR(10) NULL,
            MODIFY kota_kab_id VARCHAR(10) NULL,
            MODIFY kecamatan_id VARCHAR(10) NULL,
            MODIFY desa_id VARCHAR(15) NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE santris
            MODIFY provinsi_id VARCHAR(2) NULL,
            MODIFY kota_kab_id VARCHAR(4) NULL,
            MODIFY kecamatan_id VARCHAR(6) NULL,
            MODIFY desa_id VARCHAR(10) NULL
        ");
    }
};
