<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    protected $fillable = [
        'kode',
        'tipe',
        'nilai',
        'gelombang_id',
        'maks_pakai',
        'terpakai',
        'berlaku_sampai',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'berlaku_sampai' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function gelombang(): BelongsTo
    {
        return $this->belongsTo(Gelombang::class);
    }

    public function santris(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    public function labelDiskon(): string
    {
        return $this->tipe === 'persen'
            ? $this->nilai.'%'
            : 'Rp '.number_format($this->nilai, 0, ',', '.');
    }

    public function hitungDiskon(int $nominalDasar): int
    {
        if ($this->tipe === 'persen') {
            return (int) min($nominalDasar, round($nominalDasar * $this->nilai / 100));
        }

        return (int) min($nominalDasar, $this->nilai);
    }

    public function hitungJumlahBayar(int $nominalDasar): int
    {
        return max(0, $nominalDasar - $this->hitungDiskon($nominalDasar));
    }

    public function masihBerlaku(?Gelombang $gelombang = null): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->berlaku_sampai && $this->berlaku_sampai->isPast()) {
            return false;
        }

        if ($this->maks_pakai !== null && $this->terpakai >= $this->maks_pakai) {
            return false;
        }

        if ($this->gelombang_id && $gelombang && $this->gelombang_id !== $gelombang->id) {
            return false;
        }

        return true;
    }

    public static function findValidByCode(string $code, ?Gelombang $gelombang = null): ?self
    {
        $voucher = static::query()
            ->where('kode', strtoupper(trim($code)))
            ->first();

        if (! $voucher || ! $voucher->masihBerlaku($gelombang)) {
            return null;
        }

        return $voucher;
    }
}
