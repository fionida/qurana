<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranRiwayat extends Model
{
    protected $table = 'pembayaran_riwayat';

    protected $fillable = [
        'santri_id',
        'nominal_dasar',
        'diskon',
        'jumlah_bayar',
        'metode',
        'voucher_id',
        'verified_by',
        'verified_at',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
