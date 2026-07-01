<?php

use App\Http\Controllers\Admin\BrandingController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LembagaController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PengajarController;
use App\Http\Controllers\Admin\PhotoSheetController;
use App\Http\Controllers\Admin\RekeningController;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RegistrationController::class, 'create'])->name('home');
Route::get('/daftar', [RegistrationController::class, 'create'])->name('registration.create');
Route::post('/daftar', [RegistrationController::class, 'store'])->name('registration.store');
Route::get('/daftar/sukses/{santri}', [RegistrationController::class, 'success'])->name('registration.success');
Route::post('/daftar/{santri}/bukti-transfer', [RegistrationController::class, 'uploadBukti'])->name('registration.upload-bukti');

Route::prefix('api/wilayah')->name('api.wilayah.')->group(function () {
    Route::get('/provinces', [WilayahController::class, 'provinces'])->name('provinces');
    Route::get('/regencies/{provinceId}', [WilayahController::class, 'regencies'])->name('regencies');
    Route::get('/districts/{regencyId}', [WilayahController::class, 'districts'])->name('districts');
    Route::get('/villages/{districtId}', [WilayahController::class, 'villages'])->name('villages');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/santris', [SantriController::class, 'index'])->name('santris.index');
    Route::get('/santris/{santri}', [SantriController::class, 'show'])->name('santris.show');
    Route::delete('/santris/{santri}', [SantriController::class, 'destroy'])->name('santris.destroy');
    Route::get('/pembayaran', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/pembayaran/{santri}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/pembayaran/{santri}/verifikasi', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::get('/pembayaran/{santri}/kwitansi', [PaymentController::class, 'kwitansi'])->name('payments.kwitansi');
    Route::get('/sertifikat', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/sertifikat/{santri}', [CertificateController::class, 'print'])->name('certificates.print');
    Route::get('/foto-peserta', [PhotoSheetController::class, 'index'])->name('photo-sheets.index');
    Route::get('/foto-peserta/cetak', [PhotoSheetController::class, 'print'])->name('photo-sheets.print');
    Route::get('/rekening', [RekeningController::class, 'edit'])->name('rekening.edit');
    Route::put('/rekening', [RekeningController::class, 'update'])->name('rekening.update');
    Route::get('/branding', [BrandingController::class, 'edit'])->name('branding.edit');
    Route::put('/branding', [BrandingController::class, 'update'])->name('branding.update');
    Route::delete('/branding', [BrandingController::class, 'destroy'])->name('branding.destroy');
    Route::resource('lembagas', LembagaController::class)->except(['show']);
    Route::resource('pengajars', PengajarController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
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
