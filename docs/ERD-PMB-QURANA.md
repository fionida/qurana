# ERD — Sistem PMB / Sertifikasi Guru Qurana

Dokumen ini menggambarkan **model data target** setelah perombakan (alur PMB lengkap).  
Legenda implementasi:

| Simbol | Arti |
|--------|------|
| **Hijau / “Sudah ada”** | Tabel atau kolom sudah ada di aplikasi (nama bisa berbeda) |
| **Biru / “Perluas”** | Tabel ada, kolom atau relasi perlu ditambah |
| **Abu / “Baru”** | Entitas baru untuk perombakan |

Mapping cepat: `pendaftar` ≈ tabel `santris` saat ini (rename opsional).

---

## Diagram relasi (target)

```mermaid
erDiagram
    users ||--o{ pendaftar : "verifikasi / input nilai"
    users {
        bigint id PK
        string name
        string email UK
        string password
        enum role "admin|bendahara|panitia_tes|operator"
        timestamps created_at
    }

    program ||--o{ gelombang : "memiliki gelombang"
    program {
        bigint id PK
        string nama
        string slug UK
        text deskripsi "nullable"
        string tagline "nullable"
        boolean butuh_seleksi_tes
        boolean butuh_kelulusan
        boolean butuh_sertifikat_resmi
        boolean is_active
        smallint urutan
        timestamps created_at
    }

    gelombang ||--o{ pendaftar : "mendaftar pada"
    gelombang ||--o{ gelombang_komponen_tes : "mendefinisikan"
    gelombang ||--o{ gelombang_materi_sertifikat : "materi halaman belakang"
    gelombang {
        bigint id PK
        bigint program_id FK
        string nama
        boolean is_active
        boolean is_registration_open
        date pendaftaran_buka
        date pendaftaran_tutup
        unsigned_int kuota "nullable"
        unsigned_int biaya_pendaftaran
        date jadwal_tes_mulai "nullable"
        date jadwal_tes_selesai "nullable"
        string lokasi_tes "nullable"
        decimal nilai_lulus_minimal "nullable"
        date tanggal_sertifikasi_mulai
        date tanggal_sertifikasi_selesai
        date tanggal_terbit_masehi
        string tanggal_terbit_hijriyah
        string kota_terbit
        string kode_batch
        string jenis_nomor
        unsigned_int nomor_urut_berikutnya
        string template_halaman_1 "nullable"
        string template_halaman_2 "nullable"
        boolean template_siap_cetak
        json overlay_layout "nullable"
        timestamps created_at
    }

    pendaftar }o--|| gelombang : "gelombang_id"
    pendaftar }o--o| lembaga : "lembaga_id nullable"
    pendaftar ||--o{ nilai_tes : "memiliki"
    pendaftar ||--o{ dokumen_pendaftar : "unggah"
    pendaftar ||--o{ log_status_pendaftar : "riwayat"
    pendaftar ||--o| sertifikat_pendaftar : "0..1 terbit"
    pendaftar {
        bigint id PK
        bigint gelombang_id FK
        bigint lembaga_id FK "nullable, baru"
        string nomor_pendaftaran UK
        string nama_lengkap
        string tempat_lahir
        date tanggal_lahir
        text alamat
        string provinsi_id
        string provinsi
        string kota_kab_id
        string kota_kab
        string kecamatan_id
        string kecamatan
        string desa_id
        string desa
        string lembaga "asal lembaga pendaftar (teks; legacy, migrasi ke FK)"
        enum jenis_kelamin
        string no_wa
        string email
        string pas_foto
        enum status_pendaftar "pipeline PMB"
        enum metode_pembayaran
        enum status_pembayaran
        string bukti_transfer "nullable"
        timestamp verified_at "nullable"
        bigint verified_by FK "nullable"
        enum status_kelulusan "belum_tes|lulus|tidak_lulus"
        timestamp diumumkan_lulus_at "nullable"
        bigint diumumkan_lulus_by FK "nullable"
        decimal nilai_akhir "nullable"
        timestamps created_at
    }

    sertifikat_pendaftar ||--|| pendaftar : "pendaftar_id UK"
    sertifikat_pendaftar {
        bigint id PK
        bigint pendaftar_id FK UK
        unsigned_int nomor_sertifikat_urut
        string niq UK "nullable"
        string nomor_sertifikat UK "nullable"
        timestamp diterbitkan_at
        bigint diterbitkan_by FK "nullable"
    }

    gelombang_komponen_tes ||--o{ nilai_tes : "dinilai"
    gelombang_komponen_tes {
        bigint id PK
        bigint gelombang_id FK
        unsigned_tinyint urutan
        string nama_komponen
        decimal bobot "nullable"
        decimal nilai_maksimal
        timestamps created_at
    }

    nilai_tes {
        bigint id PK
        bigint pendaftar_id FK
        bigint gelombang_komponen_tes_id FK
        decimal nilai
        text catatan "nullable"
        bigint diinput_oleh FK "users"
        timestamps created_at
        unique pendaftar_komponen "pendaftar_id + komponen_id"
    }

    gelombang_materi_sertifikat {
        bigint id PK
        bigint gelombang_id FK
        unsigned_tinyint urutan
        string nama_materi
        string durasi "nullable"
        unsigned_smallint jpl "nullable"
        timestamps created_at
    }

    dokumen_pendaftar {
        bigint id PK
        bigint pendaftar_id FK
        string jenis "pas_foto|ktp|bukti_transfer|lainnya"
        string path
        string original_name "nullable"
        timestamps created_at
    }

    log_status_pendaftar {
        bigint id PK
        bigint pendaftar_id FK
        string status_dari "nullable"
        string status_ke
        bigint user_id FK "nullable"
        text keterangan "nullable"
        timestamps created_at
    }

    lembaga ||--o{ pendaftar : "opsional FK"
    lembaga {
        bigint id PK
        string nama UK
        boolean is_active
        timestamps created_at
    }

    pengajar {
        bigint id PK
        string nama_lengkap
        string nip UK "nullable"
        enum jenis_kelamin "nullable"
        string no_wa "nullable"
        string email "nullable"
        string lembaga "nullable"
        boolean is_active
        timestamps created_at
    }

    settings {
        bigint id PK
        string key UK
        text value
        timestamps created_at
    }
```

---

## Alur status (`pendaftar.status_pendaftar`)

Usulan enum (disepakati saat implementasi):

```text
terdaftar → menunggu_pembayaran → lunas → mengikuti_tes → lulus | tidak_lulus → sertifikat_diterbitkan
```

| Status | Keterangan |
|--------|------------|
| `terdaftar` | Form submitted |
| `menunggu_pembayaran` | Belum lunas / menunggu verifikasi |
| `lunas` | Bendahara verifikasi |
| `mengikuti_tes` | Hadir / sedang proses penilaian |
| `lulus` / `tidak_lulus` | Setelah tes & aturan gelombang |
| `sertifikat_diterbitkan` | Nomor sertifikat + NIQ terisi |

`status_pembayaran` tetap bisa dipakai (`pending` / `lunas`) selama migrasi; lama keluar digabung ke pipeline di atas.

---

## Mapping dari database saat ini

| Target | Sekarang | Aksi |
|--------|----------|------|
| `pendaftar` | `santris` | Rename atau alias model; tambah kolom status & kelulusan |
| `gelombang` | `gelombangs` | Tambah kuota, biaya, jadwal tes, nilai lulus, tanggal buka/tutup |
| `gelombang_materi_sertifikat` | `gelombang_materis` | Rename opsional |
| `sertifikat_pendaftar` | kolom di `santris` | Pisah ke tabel sendiri (opsional, lebih rapi) |
| `gelombang_komponen_tes` + `nilai_tes` | — | **Baru** |
| `dokumen_pendaftar` | `pas_foto`, `bukti_transfer` | Normalisasi ke tabel dokumen |
| `log_status_pendaftar` | — | **Baru** (audit) |
| `lembaga` | `lembagas` + string `lembaga` | FK `lembaga_id` |
| `settings` | `settings` | Tetap (rekening default, branding) |
| `users` | `users` | Tambah `role` |
| `pengajar` | `pengajars` | Master panitia/pengajar (relasi ke user opsional) |

---

## Relasi kardinalitas (ringkas)

```text
gelombang 1 ──< N pendaftar
gelombang 1 ──< N gelombang_komponen_tes
gelombang 1 ──< N gelombang_materi_sertifikat
pendaftar   1 ──< N nilai_tes
komponen    1 ──< N nilai_tes
pendaftar   1 ──< N dokumen_pendaftar
pendaftar   1 ──< N log_status_pendaftar
pendaftar   1 ─── 1 sertifikat_pendaftar (setelah lulus)
users       1 ──< N pendaftar (verified_by)
users       1 ──< N nilai_tes (diinput_oleh)
lembaga     1 ──< N pendaftar (opsional)
```

---

## Modul aplikasi ↔ entitas

| Modul admin | Entitas utama |
|-------------|----------------|
| Form pendaftaran | `pendaftar`, `gelombang`, `dokumen_pendaftar` |
| Pembayaran & kwitansi | `pendaftar`, `settings` |
| Seleksi / nilai tes | `gelombang_komponen_tes`, `nilai_tes` |
| Kelulusan | `pendaftar`, `log_status_pendaftar` |
| Sertifikat | `gelombang`, `gelombang_materi_sertifikat`, `sertifikat_pendaftar` |
| Master | `lembaga`, `pengajar`, `users`, `settings` |

---

## Catatan desain

1. **Gelombang sebagai root** — biaya, kuota, tes, template sertifikat, dan materi per angkatan.  
2. **Nomor pendaftaran ≠ nomor sertifikat** — sudah sesuai praktik PMB; sertifikat di tabel terpisah atau kolom khusus setelah `lulus`.  
3. **Nilai tes** — satu baris per `(pendaftar, komponen)`; nilai akhir bisa dihitung dari bobot atau diinput manual.  
4. **Settings global** — fallback rekening/biaya jika gelombang tidak override `biaya_pendaftaran`.

Dibuat: 2026-07-31 — revisi bersama tim sebelum migrasi besar.
