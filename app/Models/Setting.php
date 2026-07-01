<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }

    public static function set(string $key, string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function rekening(): array
    {
        return [
            'bank' => static::get('rekening_bank', config('qurana.rekening.bank')),
            'nomor' => static::get('rekening_nomor', config('qurana.rekening.nomor')),
            'atas_nama' => static::get('rekening_atas_nama', config('qurana.rekening.atas_nama')),
        ];
    }

    public static function biayaPendaftaran(): int
    {
        $value = static::get('biaya_pendaftaran');

        if ($value === null || $value === '') {
            return (int) config('qurana.biaya_pendaftaran');
        }

        return (int) $value;
    }

    public static function logoPath(): ?string
    {
        $path = static::get('site_logo');

        return $path ?: null;
    }

    public static function logoUrl(): ?string
    {
        $path = static::logoPath();

        return $path ? asset('storage/'.$path) : null;
    }
}
