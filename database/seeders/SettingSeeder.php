<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('rekening_bank', config('qurana.rekening.bank'));
        Setting::set('rekening_nomor', config('qurana.rekening.nomor'));
        Setting::set('rekening_atas_nama', config('qurana.rekening.atas_nama'));
    }
}
