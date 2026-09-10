<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GelombangKomponenTes extends Model
{
    protected $table = 'gelombang_komponen_tes';

    protected $fillable = [
        'gelombang_id',
        'urutan',
        'nama_komponen',
        'bobot',
        'nilai_maksimal',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
            'bobot' => 'decimal:2',
            'nilai_maksimal' => 'decimal:2',
        ];
    }

    public function gelombang(): BelongsTo
    {
        return $this->belongsTo(Gelombang::class);
    }

    public function nilaiTes(): HasMany
    {
        return $this->hasMany(NilaiTes::class);
    }
}
