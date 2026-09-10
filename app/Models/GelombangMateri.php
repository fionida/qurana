<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GelombangMateri extends Model
{
    protected $fillable = [
        'gelombang_id',
        'urutan',
        'nama_materi',
        'durasi',
        'jpl',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
            'jpl' => 'integer',
        ];
    }

    public function gelombang(): BelongsTo
    {
        return $this->belongsTo(Gelombang::class);
    }

    public function durasiDisplay(): string
    {
        $durasi = trim((string) $this->durasi);

        if ($durasi === '') {
            return '';
        }

        if (! str_ends_with($durasi, "'") && ! str_ends_with($durasi, '’')) {
            return $durasi."'";
        }

        return $durasi;
    }

    public static function parseDurasiMinutes(?string $durasi): int
    {
        if ($durasi === null || trim($durasi) === '') {
            return 0;
        }

        return (int) preg_replace('/\D/', '', $durasi);
    }
}
