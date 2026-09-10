<?php

namespace App\Models;

use App\Support\CertificateTemplatePreview;
use App\Support\RomanMonth;
use App\Support\SertifikatLayout;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Gelombang extends Model
{
    protected $fillable = [
        'program_id',
        'nama',
        'is_active',
        'is_registration_open',
        'biaya_pendaftaran',
        'kuota',
        'pendaftaran_buka',
        'pendaftaran_tutup',
        'jadwal_tes_mulai',
        'jadwal_tes_selesai',
        'lokasi_tes',
        'nilai_lulus_minimal',
        'tanggal_sertifikasi_mulai',
        'tanggal_sertifikasi_selesai',
        'tanggal_terbit_masehi',
        'tanggal_terbit_hijriyah',
        'kota_terbit',
        'kode_batch',
        'jenis_nomor',
        'nomor_urut_berikutnya',
        'template_halaman_1',
        'template_halaman_2',
        'template_siap_cetak',
        'overlay_layout',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_registration_open' => 'boolean',
            'biaya_pendaftaran' => 'integer',
            'kuota' => 'integer',
            'pendaftaran_buka' => 'date',
            'pendaftaran_tutup' => 'date',
            'jadwal_tes_mulai' => 'date',
            'jadwal_tes_selesai' => 'date',
            'nilai_lulus_minimal' => 'decimal:2',
            'tanggal_sertifikasi_mulai' => 'date',
            'tanggal_sertifikasi_selesai' => 'date',
            'tanggal_terbit_masehi' => 'date',
            'nomor_urut_berikutnya' => 'integer',
            'template_siap_cetak' => 'boolean',
            'overlay_layout' => 'array',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function santris(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    public function materis(): HasMany
    {
        return $this->hasMany(GelombangMateri::class)->orderBy('urutan');
    }

    public function komponenTes(): HasMany
    {
        return $this->hasMany(GelombangKomponenTes::class)->orderBy('urutan');
    }

    public function ensureKomponenTesSeeded(): void
    {
        if ($this->komponenTes()->exists()) {
            return;
        }

        foreach (config('komponen_tes.defaults') as $item) {
            $this->komponenTes()->create([
                'urutan' => $item['urutan'],
                'nama_komponen' => $item['nama_komponen'],
                'bobot' => $item['bobot'],
                'nilai_maksimal' => $item['nilai_maksimal'],
            ]);
        }
    }

    public function biayaPendaftaranEfektif(): int
    {
        if ($this->biaya_pendaftaran !== null && $this->biaya_pendaftaran > 0) {
            return (int) $this->biaya_pendaftaran;
        }

        return Setting::biayaPendaftaran();
    }

    public function ensureMaterisSeeded(): void
    {
        if ($this->materis()->exists()) {
            return;
        }

        foreach (config('sertifikat_materi.defaults') as $item) {
            $this->materis()->create([
                'urutan' => $item['urutan'],
                'nama_materi' => $item['nama_materi'],
                'durasi' => null,
                'jpl' => null,
            ]);
        }
    }

    public function totalMateriJpl(): int
    {
        return (int) $this->materis()->sum('jpl');
    }

    public function totalMateriDurasiLabel(): string
    {
        $minutes = 0;

        foreach ($this->materis()->get() as $materi) {
            $minutes += GelombangMateri::parseDurasiMinutes($materi->durasi);
        }

        return $minutes > 0 ? $minutes."'" : '';
    }

    public function sisaKuotaPendaftaran(): ?int
    {
        if ($this->kuota === null) {
            return null;
        }

        return max(0, $this->kuota - $this->santris()->count());
    }

    public static function openForRegistration(?int $programId = null): ?self
    {
        $today = now()->startOfDay();

        $query = static::query()
            ->where('is_active', true)
            ->where('is_registration_open', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('pendaftaran_buka')->orWhereDate('pendaftaran_buka', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('pendaftaran_tutup')->orWhereDate('pendaftaran_tutup', '>=', $today);
            })
            ->orderByDesc('id');

        if ($programId !== null) {
            $query->where('program_id', $programId);
        }

        $candidates = $query->get();

        foreach ($candidates as $gelombang) {
            if ($gelombang->kuota !== null) {
                $terisi = $gelombang->santris()->count();
                if ($terisi >= $gelombang->kuota) {
                    continue;
                }
            }

            return $gelombang;
        }

        return null;
    }

    public function tanggalSertifikasiLabel(): string
    {
        $mulai = $this->tanggal_sertifikasi_mulai->translatedFormat('d F Y');
        $selesai = $this->tanggal_sertifikasi_selesai;

        if ($this->tanggal_sertifikasi_mulai->isSameDay($selesai)) {
            return $mulai;
        }

        if ($this->tanggal_sertifikasi_mulai->month === $selesai->month
            && $this->tanggal_sertifikasi_mulai->year === $selesai->year) {
            return $this->tanggal_sertifikasi_mulai->translatedFormat('d')
                .' – '.$selesai->translatedFormat('d F Y');
        }

        return $this->tanggal_sertifikasi_mulai->translatedFormat('d F Y')
            .' – '.$selesai->translatedFormat('d F Y');
    }

    public function tanggalTerbitMasehiLabel(): string
    {
        return $this->tanggal_terbit_masehi->translatedFormat('d F Y').' M';
    }

    public static function formatNiq(int $urut, string $kodeBatch, CarbonInterface $tanggalLahir): string
    {
        $seq = str_pad((string) $urut, 4, '0', STR_PAD_LEFT);
        $suffix = $tanggalLahir->format('dmy');

        return "{$seq}.{$kodeBatch}.{$suffix}";
    }

    public function formatNomorSertifikat(int $urut): string
    {
        $seq = str_pad((string) $urut, 4, '0', STR_PAD_LEFT);
        $bulan = RomanMonth::fromMonth((int) $this->tanggal_terbit_masehi->format('n'));
        $tahun = $this->tanggal_terbit_masehi->format('Y');

        return "{$seq}/{$this->kode_batch}/{$this->jenis_nomor}/{$bulan}/{$tahun}";
    }

    public function assignCertificateNumbers(Santri $santri): void
    {
        if ($santri->gelombang_id !== $this->id) {
            throw new \InvalidArgumentException('Gelombang pendidik tidak sesuai.');
        }

        if ($santri->nomor_sertifikat_urut !== null) {
            return;
        }

        DB::transaction(function () use ($santri) {
            /** @var self $gelombang */
            $gelombang = static::query()->lockForUpdate()->findOrFail($this->id);

            $urut = (int) $gelombang->nomor_urut_berikutnya;

            $santri->update([
                'nomor_sertifikat_urut' => $urut,
                'niq' => static::formatNiq($urut, $gelombang->kode_batch, $santri->tanggal_lahir),
                'nomor_sertifikat' => $gelombang->formatNomorSertifikat($urut),
            ]);

            $gelombang->update([
                'nomor_urut_berikutnya' => $urut + 1,
            ]);
        });
    }

    public function templateHalaman1Path(): ?string
    {
        return CertificateTemplatePreview::resolveAbsolutePath($this->template_halaman_1);
    }

    public function isTemplateHalaman1Pdf(): bool
    {
        if (! $this->template_halaman_1) {
            return false;
        }

        return strtolower(pathinfo($this->template_halaman_1, PATHINFO_EXTENSION)) === 'pdf';
    }

    public function templateHalaman2Path(): ?string
    {
        return CertificateTemplatePreview::resolveAbsolutePath($this->template_halaman_2);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function layoutHalaman1(): array
    {
        return $this->overlay_layout['halaman_1'] ?? SertifikatLayout::defaultHalaman1();
    }

    /**
     * @return array<string, mixed>
     */
    public function layoutHalaman2(): array
    {
        $stored = $this->overlay_layout['halaman_2'] ?? [];

        return SertifikatLayout::normalizeHalaman2($stored);
    }
}
