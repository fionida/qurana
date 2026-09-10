<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SantriDokumen extends Model
{
    protected $table = 'santri_dokumen';

    protected $fillable = [
        'santri_id',
        'jenis',
        'path',
        'original_name',
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function jenisLabel(): string
    {
        return config('persyaratan.jenis')[$this->jenis] ?? $this->jenis;
    }

    public function url(): string
    {
        return asset('storage/'.$this->path);
    }

    protected static function booted(): void
    {
        static::deleting(function (self $doc) {
            Storage::disk('public')->delete($doc->path);
        });
    }
}
