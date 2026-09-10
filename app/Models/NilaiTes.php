<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiTes extends Model
{
    protected $table = 'nilai_tes';

    protected $fillable = [
        'santri_id',
        'gelombang_komponen_tes_id',
        'nilai',
        'catatan',
        'diinput_oleh',
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
        ];
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function komponen(): BelongsTo
    {
        return $this->belongsTo(GelombangKomponenTes::class, 'gelombang_komponen_tes_id');
    }

    public function penginput(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }
}
