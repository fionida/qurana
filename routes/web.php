<?php

use App\Http\Controllers\Admin\BrandingController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GelombangController;
use App\Http\Controllers\Admin\GelombangKomponenTesController;
use App\Http\Controllers\Admin\GelombangLayoutController;
use App\Http\Controllers\Admin\GelombangMateriController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LembagaController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PengajarController;
use App\Http\Controllers\Admin\PhotoSheetController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\ProgramPenandatanganController;
use App\Http\Controllers\Admin\RekeningController;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\TesController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\KartuPesertaController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\StatusCheckController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

Route::get('/cek-status', [StatusCheckController::class, 'show'])->name('status-check.show');
Route::post('/cek-status', [StatusCheckController::class, 'lookup'])->name('status-check.lookup');

Route::get('/', [PortalController::class, 'index'])->name('portal.home');
Route::get('/program/{program:slug}', [PortalController::class, 'show'])->name('portal.program.show');
Route::redirect('/daftar', '/')->name('registration.create');
Route::get('/daftar/sukses/{santri}', [RegistrationController::class, 'success'])->name('registration.success');
Route::post('/daftar/{santri}/bukti-transfer', [RegistrationController::class, 'uploadBukti'])->name('registration.upload-bukti');
Route::get('/daftar/{program:slug}', [RegistrationController::class, 'create'])->name('registration.form');
Route::post('/daftar/{program:slug}', [RegistrationController::class, 'store'])->name('registration.store');
Route::get('/kartu-peserta/{santri}', [KartuPesertaController::class, 'show'])->name('kartu-peserta.show');

// Alias lama: arahkan ke portal
Route::redirect('/home', '/')->name('home');

Route::prefix('api/wilayah')->name('api.wilayah.')->group(function () {
    Route::get('/provinces', [WilayahController::class, 'provinces'])->name('provinces');
    Route::get('/regencies/{provinceId}', [WilayahController::class, 'regencies'])->name('regencies');
    Route::get('/districts/{regencyId}', [WilayahController::class, 'districts'])->name('districts');
    Route::get('/villages/{districtId}', [WilayahController::class, 'villages'])->name('villages');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('role:admin,bendahara,panitia_tes,operator_cetak')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::middleware('role:admin,bendahara,panitia_tes')->group(function () {
        Route::get('/santris', [SantriController::class, 'index'])->name('santris.index');
        Route::get('/santris/export', [SantriController::class, 'export'])->name('santris.export');
        Route::get('/santris/{santri}', [SantriController::class, 'show'])->name('santris.show');
        Route::get('/santris/{santri}/kartu-peserta', [KartuPesertaController::class, 'adminShow'])->name('santris.kartu-peserta');
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/santris/{santri}/pas-foto', [SantriController::class, 'updatePhoto'])->name('santris.update-photo');
        Route::delete('/santris/{santri}', [SantriController::class, 'destroy'])->name('santris.destroy');
    });

    Route::middleware('role:admin,bendahara')->group(function () {
        Route::get('/pembayaran', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/pembayaran/{santri}', [PaymentController::class, 'show'])->name('payments.show');
        Route::post('/pembayaran/{santri}/verifikasi', [PaymentController::class, 'verify'])->name('payments.verify');
        Route::get('/pembayaran/{santri}/kwitansi', [PaymentController::class, 'kwitansi'])->name('payments.kwitansi');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export/pendaftar', [LaporanController::class, 'exportPendaftar'])->name('laporan.export.pendaftar');
        Route::get('/laporan/export/pembayaran', [LaporanController::class, 'exportPembayaran'])->name('laporan.export.pembayaran');
        Route::get('/laporan/export/rekonsiliasi', [LaporanController::class, 'exportRekonsiliasi'])->name('laporan.export.rekonsiliasi');
        Route::resource('vouchers', VoucherController::class)->except(['show']);
    });

    Route::middleware('role:admin,operator_cetak')->group(function () {
        Route::get('/sertifikat', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/sertifikat/gelombang/{gelombang}/massa', [CertificateController::class, 'massCetak'])->name('certificates.mass');
        Route::put('/sertifikat/{santri}/gelombang', [CertificateController::class, 'updateGelombang'])->name('certificates.update-gelombang');
        Route::get('/sertifikat/{santri}/preview', [CertificateController::class, 'preview'])->name('certificates.preview');
        Route::get('/sertifikat/{santri}', [CertificateController::class, 'print'])->name('certificates.print');
        Route::get('/foto-peserta', [PhotoSheetController::class, 'index'])->name('photo-sheets.index');
        Route::get('/foto-peserta/cetak', [PhotoSheetController::class, 'print'])->name('photo-sheets.print');
        Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
        Route::put('/templates/{slot}', [TemplateController::class, 'update'])->name('templates.update');
        Route::delete('/templates/{slot}', [TemplateController::class, 'destroy'])->name('templates.destroy');
        Route::get('/templates/{slot}/download', [TemplateController::class, 'download'])->name('templates.download');
        Route::get('/gelombangs', [GelombangController::class, 'index'])->name('gelombangs.index');
        Route::get('/gelombangs/{gelombang}/template-depan', [GelombangLayoutController::class, 'templateDepan'])->name('gelombangs.template-depan');
        Route::get('/gelombangs/{gelombang}/layout', [GelombangLayoutController::class, 'edit'])->name('gelombangs.layout.edit');
        Route::put('/gelombangs/{gelombang}/layout', [GelombangLayoutController::class, 'update'])->name('gelombangs.layout.update');
        Route::delete('/gelombangs/{gelombang}/layout', [GelombangLayoutController::class, 'reset'])->name('gelombangs.layout.reset');
        Route::get('/gelombangs/{gelombang}/materi', [GelombangMateriController::class, 'edit'])->name('gelombangs.materi.edit');
        Route::put('/gelombangs/{gelombang}/materi', [GelombangMateriController::class, 'update'])->name('gelombangs.materi.update');
    });

    Route::middleware('role:admin,panitia_tes')->group(function () {
        Route::get('/tes', [TesController::class, 'index'])->name('tes.index');
        Route::post('/tes/{gelombang}/nilai', [TesController::class, 'storeNilai'])->name('tes.nilai.store');
        Route::get('/tes/{gelombang}/rekap', [TesController::class, 'rekap'])->name('tes.rekap');
        Route::get('/tes/{gelombang}/daftar-hadir', [TesController::class, 'daftarHadir'])->name('tes.daftar-hadir');
        Route::get('/tes/{gelombang}/berita-acara', [TesController::class, 'beritaAcara'])->name('tes.berita-acara');
        Route::post('/tes/{gelombang}/kelulusan', [TesController::class, 'tentukanKelulusan'])->name('tes.kelulusan');
        Route::get('/laporan/export/nilai/{gelombang}', [LaporanController::class, 'exportNilaiTes'])->name('laporan.export.nilai');
        Route::get('/gelombangs/{gelombang}/komponen-tes', [GelombangKomponenTesController::class, 'edit'])->name('gelombangs.komponen-tes.edit');
        Route::post('/gelombangs/{gelombang}/komponen-tes', [GelombangKomponenTesController::class, 'store'])->name('gelombangs.komponen-tes.store');
        Route::put('/gelombangs/{gelombang}/komponen-tes', [GelombangKomponenTesController::class, 'update'])->name('gelombangs.komponen-tes.update');
        Route::delete('/gelombangs/{gelombang}/komponen-tes/{komponenTes}', [GelombangKomponenTesController::class, 'destroy'])->name('gelombangs.komponen-tes.destroy');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/rekening', [RekeningController::class, 'edit'])->name('rekening.edit');
        Route::put('/rekening', [RekeningController::class, 'update'])->name('rekening.update');
        Route::get('/branding', [BrandingController::class, 'edit'])->name('branding.edit');
        Route::put('/branding', [BrandingController::class, 'update'])->name('branding.update');
        Route::delete('/branding', [BrandingController::class, 'destroy'])->name('branding.destroy');
        Route::resource('lembagas', LembagaController::class)->except(['show']);
        Route::resource('programs', ProgramController::class)->except(['show']);
        Route::get('/programs/{program}/penandatangan', [ProgramPenandatanganController::class, 'index'])->name('programs.penandatangan.index');
        Route::post('/programs/{program}/penandatangan', [ProgramPenandatanganController::class, 'store'])->name('programs.penandatangan.store');
        Route::put('/programs/{program}/penandatangan/{penandatangan}', [ProgramPenandatanganController::class, 'update'])->name('programs.penandatangan.update');
        Route::delete('/programs/{program}/penandatangan/{penandatangan}', [ProgramPenandatanganController::class, 'destroy'])->name('programs.penandatangan.destroy');
        Route::get('/gelombangs/suggest-hijri', [GelombangController::class, 'suggestHijri'])->name('gelombangs.suggest-hijri');
        Route::resource('gelombangs', GelombangController::class)->except(['show', 'index']);
        Route::resource('pengajars', PengajarController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
    });
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
