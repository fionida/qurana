<?php

namespace Database\Seeders;

use App\Models\Lembaga;
use Illuminate\Database\Seeder;

class LembagaSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('qurana.lembaga') as $nama) {
            if ($nama === 'Lainnya') {
                continue;
            }

            Lembaga::query()->firstOrCreate(
                ['nama' => $nama],
                ['is_active' => true]
            );
        }
    }
}
