<?php

namespace App\Support;

use App\Models\LogStatusPendaftar;
use App\Models\Santri;

class PendaftarStatusLog
{
    public static function record(
        Santri $santri,
        string $statusKe,
        ?string $statusDari = null,
        ?string $keterangan = null,
        ?int $userId = null,
    ): void {
        LogStatusPendaftar::create([
            'santri_id' => $santri->id,
            'status_dari' => $statusDari ?? $santri->getOriginal('status_pendaftar'),
            'status_ke' => $statusKe,
            'user_id' => $userId ?? auth()->id(),
            'keterangan' => $keterangan,
        ]);
    }
}
