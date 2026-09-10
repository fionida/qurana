<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Program extends Model
{
    protected $fillable = [
        'nama',
        'judul_sertifikat',
        'slug',
        'deskripsi',
        'tagline',
        'butuh_seleksi_tes',
        'butuh_kelulusan',
        'butuh_sertifikat_resmi',
        'is_active',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'butuh_seleksi_tes' => 'boolean',
            'butuh_kelulusan' => 'boolean',
            'butuh_sertifikat_resmi' => 'boolean',
            'is_active' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function gelombangs(): HasMany
    {
        return $this->hasMany(Gelombang::class);
    }

    public function penandatangans(): HasMany
    {
        return $this->hasMany(Penandatangan::class)->orderBy('urutan');
    }

    public function judulSertifikat(): string
    {
        $judul = trim((string) ($this->judul_sertifikat ?? ''));

        return $judul !== '' ? $judul : $this->nama;
    }

    public function scopeActiveOrdered($query)
    {
        return $query->where('is_active', true)->orderBy('urutan')->orderBy('nama');
    }

    public static function slugFromNama(string $nama): string
    {
        $base = Str::slug($nama);
        $slug = $base;
        $i = 2;

        while (static::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function openGelombangForRegistration(): ?Gelombang
    {
        return Gelombang::openForRegistration($this->id);
    }

    public function alurRingkasLabel(): string
    {
        $parts = ['Pendaftaran', 'Pembayaran'];

        if ($this->butuh_seleksi_tes) {
            $parts[] = 'Seleksi tes';
        }

        if ($this->butuh_kelulusan) {
            $parts[] = 'Kelulusan';
        }

        if ($this->butuh_sertifikat_resmi) {
            $parts[] = 'Sertifikat resmi';
        }

        return implode(' → ', $parts);
    }
}
