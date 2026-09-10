<?php

namespace App\Models;

use App\Support\PendaftarStatusLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Santri extends Model
{
    protected $fillable = [
        'gelombang_id',
        'nomor_sertifikat_urut',
        'niq',
        'nomor_sertifikat',
        'nomor_pendaftaran',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'provinsi_id',
        'provinsi',
        'kota_kab_id',
        'kota_kab',
        'kecamatan_id',
        'kecamatan',
        'desa_id',
        'desa',
        'lembaga',
        'jenis_kelamin',
        'no_wa',
        'email',
        'pas_foto',
        'metode_pembayaran',
        'bukti_transfer',
        'status_pembayaran',
        'voucher_id',
        'jumlah_bayar',
        'status_pendaftar',
        'status_kelulusan',
        'nilai_akhir',
        'verified_at',
        'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'nilai_akhir' => 'decimal:2',
            'verified_at' => 'datetime',
        ];
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function gelombang(): BelongsTo
    {
        return $this->belongsTo(Gelombang::class);
    }

    public function nilaiTes(): HasMany
    {
        return $this->hasMany(NilaiTes::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function pembayaranRiwayat(): HasMany
    {
        return $this->hasMany(PembayaranRiwayat::class);
    }

    public function logStatus(): HasMany
    {
        return $this->hasMany(LogStatusPendaftar::class)->orderByDesc('created_at');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(SantriDokumen::class);
    }

    public function nominalPendaftaranDasar(): int
    {
        return $this->gelombang?->biayaPendaftaranEfektif() ?? Setting::biayaPendaftaran();
    }

    public function jumlahBayarEfektif(): int
    {
        if ($this->jumlah_bayar !== null) {
            return (int) $this->jumlah_bayar;
        }

        $dasar = $this->nominalPendaftaranDasar();

        if ($this->voucher) {
            return $this->voucher->hitungJumlahBayar($dasar);
        }

        return $dasar;
    }

    protected static function booted(): void
    {
        static::created(function (Santri $santri) {
            if ($santri->status_pendaftar) {
                PendaftarStatusLog::record(
                    $santri,
                    $santri->status_pendaftar,
                    null,
                    'Pendaftaran baru',
                    null
                );
            }
        });

        static::updated(function (Santri $santri) {
            if ($santri->wasChanged('status_pendaftar') && $santri->status_pendaftar) {
                PendaftarStatusLog::record(
                    $santri,
                    $santri->status_pendaftar,
                    $santri->getOriginal('status_pendaftar'),
                );
            }
        });
    }

    public function statusPendaftarLabel(): string
    {
        return match ($this->status_pendaftar) {
            'terdaftar' => 'Terdaftar',
            'menunggu_pembayaran' => 'Menunggu pembayaran',
            'lunas' => 'Lunas',
            'mengikuti_tes' => 'Mengikuti tes',
            'lulus' => 'Lulus',
            'tidak_lulus' => 'Tidak lulus',
            'sertifikat_diterbitkan' => 'Sertifikat diterbitkan',
            default => (string) ($this->status_pendaftar ?? '-'),
        };
    }

    public function hitungDanSimpanNilaiAkhir(): ?float
    {
        $gelombang = $this->gelombang;

        if (! $gelombang) {
            return null;
        }

        $komponen = $gelombang->komponenTes()->get();
        $nilaiMap = $this->nilaiTes()->get()->keyBy('gelombang_komponen_tes_id');

        if ($komponen->isEmpty()) {
            return null;
        }

        $totalBobot = $komponen->sum(fn ($k) => (float) ($k->bobot ?? 0));
        $gunakanBobot = $totalBobot > 0;

        $akhir = 0.0;
        $countFilled = 0;

        foreach ($komponen as $k) {
            $nilai = $nilaiMap->get($k->id)?->nilai;

            if ($nilai === null) {
                continue;
            }

            $countFilled++;
            $normalized = ((float) $nilai / (float) $k->nilai_maksimal) * 100;

            if ($gunakanBobot) {
                $akhir += $normalized * ((float) $k->bobot / $totalBobot);
            } else {
                $akhir += $normalized;
            }
        }

        if ($countFilled === 0) {
            return null;
        }

        if (! $gunakanBobot) {
            $akhir /= $countFilled;
        }

        $akhir = round($akhir, 2);
        $this->update(['nilai_akhir' => $akhir]);

        return $akhir;
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getMetodePembayaranLabelAttribute(): string
    {
        return $this->metode_pembayaran === 'transfer' ? 'Transfer' : 'Bayar di Tempat';
    }

    public function getStatusPembayaranLabelAttribute(): string
    {
        return $this->status_pembayaran === 'lunas' ? 'Lunas' : 'Pending';
    }

    public function getTtlAttribute(): string
    {
        return $this->tempat_lahir.', '.$this->tanggal_lahir->translatedFormat('d F Y');
    }

    public function isLunas(): bool
    {
        return $this->status_pembayaran === 'lunas';
    }

    public function bolehCetakSertifikat(): bool
    {
        if (! $this->isLunas()) {
            return false;
        }

        $program = $this->gelombang?->program;

        if ($program && ! $program->butuh_sertifikat_resmi) {
            return false;
        }

        if ($this->nomor_sertifikat_urut !== null) {
            return true;
        }

        if ($program && ! $program->butuh_kelulusan && ! $program->butuh_seleksi_tes) {
            return true;
        }

        return in_array($this->status_pendaftar, ['lulus', 'sertifikat_diterbitkan'], true);
    }

    public function scopeEligibleForCertificate($query)
    {
        return $query
            ->where('status_pembayaran', 'lunas')
            ->whereHas('gelombang.program', fn ($p) => $p->where('butuh_sertifikat_resmi', true))
            ->where(function ($q) {
                $q->whereIn('status_pendaftar', ['lulus', 'sertifikat_diterbitkan'])
                    ->orWhereNotNull('nomor_sertifikat_urut')
                    ->orWhereHas('gelombang.program', function ($p) {
                        $p->where('butuh_kelulusan', false)->where('butuh_seleksi_tes', false);
                    });
            });
    }

    public function getAlamatLengkapAttribute(): string
    {
        $parts = array_filter([
            $this->alamat,
            $this->desa,
            $this->kecamatan,
            $this->kota_kab,
            $this->provinsi,
        ]);

        return implode(', ', $parts);
    }

    public static function generateNomorPendaftaran(): string
    {
        $year = now()->format('Y');
        $prefix = "QRN-{$year}-";

        $lastNumber = static::query()
            ->where('nomor_pendaftaran', 'like', "{$prefix}%")
            ->orderByDesc('nomor_pendaftaran')
            ->value('nomor_pendaftaran');

        $sequence = $lastNumber
            ? ((int) substr($lastNumber, -4)) + 1
            : 1;

        return $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    public static function lembagaOptions(): array
    {
        return static::query()
            ->whereNotNull('lembaga')
            ->where('lembaga', '!=', '')
            ->orderBy('lembaga')
            ->distinct()
            ->pluck('lembaga')
            ->values()
            ->all();
    }
}
