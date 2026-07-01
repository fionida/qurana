<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajar extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'nip',
        'jenis_kelamin',
        'no_wa',
        'email',
        'lembaga',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getJenisKelaminLabelAttribute(): ?string
    {
        return match ($this->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => null,
        };
    }
}
