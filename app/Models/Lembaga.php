<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    protected $fillable = ['nama', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public static function activeList(): Collection
    {
        return static::query()
            ->where('is_active', true)
            ->orderBy('nama')
            ->get();
    }

    public static function activeNames(): array
    {
        return static::activeList()->pluck('nama')->all();
    }

    public static function allNames(): array
    {
        return static::query()->orderBy('nama')->pluck('nama')->all();
    }
}
