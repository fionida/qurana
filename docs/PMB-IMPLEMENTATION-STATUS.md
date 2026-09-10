# Status implementasi PMB Qurana (2026-07-31)

Ringkasan fitur dari daftar 6 blok — status **selesai di aplikasi** vs **belum / opsional eksternal**.

## 1. Seleksi / Tes — selesai
- Master komponen tes per gelombang, input nilai, rekap/ranking, daftar hadir, berita acara (cetak), proses kelulusan otomatis.

## 2. Keuangan — selesai (inti)
- Biaya per gelombang + fallback setting global.
- **Riwayat pembayaran** (`pembayaran_riwayat`) saat verifikasi bendahara.
- **Voucher** (persen/nominal, per gelombang, kuota pakai).
- **Rekonsiliasi** export CSV (peserta lunas vs nominal/voucher).
- Integrasi **bank otomatis** — tidak (manual + export).

## 3. Dokumen & cetak — selesai (inti)
- Upload KTP & surat rekomendasi di **form daftar** (`santri_dokumen`).
- **Kartu peserta** (publik dengan tanggal lahir / admin).
- Kwitansi, sertifikat, **cetak massal** per gelombang, foto pendidik (existing).

## 4. Portal & admin — selesai (inti + multi-program)
- **Portal publik** (`/`) — daftar program kegiatan, detail program, daftar per slug (`/daftar/{slug}`).
- **Master program** (`programs`) — alur per kegiatan: tes, kelulusan, sertifikat resmi.
- **Gelombang** terhubung `program_id`; form pendaftaran terbuka **per program** (bukan global satu gelombang).
- Cek status, role admin (bendahara, panitia, operator cetak), audit **log status pendaftar**.
- Notifikasi **WA/email** — belum (butuh gateway pihak ketiga).

## 5. Laporan — selesai
- Dashboard, pembayaran, dan export CSV: filter **program** (semua gelombang) atau **gelombang** (spesifik).
- Kolom **Program** di CSV pendaftar, pembayaran, rekonsiliasi, nilai tes.

## 6. Master data — selesai (existing + perluas)
- **Program kegiatan**, gelombang (per program), master asal lembaga (`lembagas`), pengajar, user+role, rekening, branding.

## Istilah
- **Lembaga** = **asal lembaga pendaftar** (institusi tempat pendidik berasal, mis. TPQ/MDA). Kolom `santris.lembaga` dan master `lembagas` memakai makna ini.

## Migrasi
Jalankan: `php artisan migrate`

Migrasi PMB: `2026_07_31_100000_*`, `100001_*`, `100002_*`, `110000_*`, `120000_*` (programs + `gelombangs.program_id`).

## Data lama
Peserta **lunas** sebelum riwayat pembayaran: hanya status di `santris`; riwayat terisi untuk verifikasi **baru**. Opsional backfill manual via SQL jika diperlukan.
