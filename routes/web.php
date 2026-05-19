<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// IMPORT CONTROLLER ADMIN
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PemeriksaanController as AdminPemeriksaan;
use App\Http\Controllers\Admin\DataPasienController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PendaftaranController as AdminPendaftaranController;
use App\Http\Controllers\Admin\JadwalDokterController;

// IMPORT CONTROLLER DOKTER
use App\Http\Controllers\Dokter\DashboardController as DokterDashboard;
use App\Http\Controllers\Dokter\PemeriksaanController as DokterPemeriksaan;
use App\Http\Controllers\Dokter\RekamMedisController as DokterRekamMedis;

// IMPORT CONTROLLER PETUGAS
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\PemeriksaanAwalController;
use App\Http\Controllers\Petugas\PendaftaranController as PetugasPendaftaranController;
use App\Http\Controllers\Petugas\ObatController;

/*
|--------------------------------------------------------------------------
| HALAMAN AWAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH INSTANSI (ADMIN & DOKTER)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('instansi.login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('instansi.logout');
Route::post('/register-dokter', [AuthController::class, 'register'])->name('instansi.register');


/*
|--------------------------------------------------------------------------
| ROLE: ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.') 
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard'); 
        
        // Data Pasien
        Route::get('/data-pasien', [DataPasienController::class, 'index'])->name('data_pasien.index');
        Route::get('/data-pasien/{no_identitas}', [DataPasienController::class, 'detail'])->name('data_pasien.detail');

        // Pendaftaran
        Route::get('/pendaftaran', [AdminPendaftaranController::class, 'index'])->name('pendaftaran.index');
        Route::post('/pendaftaran/{id}/status', [AdminPendaftaranController::class, 'updateStatus'])->name('pendaftaran.status');

        // Pemeriksaan (Rekam Medis)
        Route::get('/pemeriksaan', [AdminPemeriksaan::class, 'index'])->name('pemeriksaan'); 
        Route::get('/pemeriksaan/{id}', [AdminPemeriksaan::class, 'show'])->name('pemeriksaan.show'); 

        // Jadwal Dokter
        Route::controller(JadwalDokterController::class)->prefix('jadwal-dokter')->group(function () {
            Route::get('/', 'index')->name('jadwal_dokter');
            Route::post('/', 'store')->name('jadwal_dokter.store');
            Route::put('/{id}', 'update')->name('jadwal_dokter.update');
            Route::post('/{id}/toggle', 'toggle')->name('jadwal_dokter.toggle');
            Route::delete('/{id}', 'destroy')->name('jadwal_dokter.destroy');
        });

        // Pembayaran Kasir
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
        Route::get('/pembayaran/detail/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
        Route::post('/pembayaran/lunasi/{id}', [PembayaranController::class, 'lunasi'])->name('pembayaran.lunasi');
        Route::get('/pembayaran/print/{id}', [PembayaranController::class, 'printStruk'])->name('pembayaran.print');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan'); 
        Route::get('/laporan/pdf/{bulan}/{tahun}', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');

    });


/*
|--------------------------------------------------------------------------
| ROLE: DOKTER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dokter'])
    ->prefix('dokter')
    ->name('dokter.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DokterDashboard::class, 'index'])->name('dashboard');

        // Daftar Pasien Yang Akan Diperiksa
        Route::get('/pasien', [DokterPemeriksaan::class, 'index'])->name('pasien');

        // Form Pemeriksaan
        Route::get('/pemeriksaan/{id}', [DokterPemeriksaan::class, 'show'])->name('pemeriksaan.show');
        Route::post('/pemeriksaan/{id}', [DokterPemeriksaan::class, 'store'])->name('pemeriksaan.store');

        // Rekam Medis Pasien Berdasarkan Grup (Yang baru diperbaiki)
        Route::get('/rekam-medis', [DokterRekamMedis::class, 'index'])->name('rekammedis');
        Route::get('/rekam-medis/{id}', [DokterRekamMedis::class, 'show'])->name('rekammedis.show');

        // Profil
        Route::view('/profil', 'dokter.profil')->name('profil');

    });


/*
|--------------------------------------------------------------------------
| ROLE: PETUGAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');

        // Data Pendaftaran
        Route::get('/pendaftaran', [PetugasPendaftaranController::class, 'index'])->name('pendaftaran.index');

        // Pemeriksaan Awal (Vitals)
        Route::get('/pemeriksaan-awal', [PemeriksaanAwalController::class, 'index'])->name('pemeriksaan_awal.index');
        Route::get('/pemeriksaan-awal/{id}', [PemeriksaanAwalController::class, 'edit'])->name('pemeriksaan_awal.edit');
        Route::put('/pemeriksaan-awal/{id}', [PemeriksaanAwalController::class, 'update'])->name('pemeriksaan_awal.update');

        // Stok Obat
        Route::get('/stok-obat', [ObatController::class, 'index'])->name('stok_obat.index');
        Route::post('/stok-obat', [ObatController::class, 'store'])->name('stok_obat.store');
        Route::put('/stok-obat/{id}', [ObatController::class, 'update'])->name('stok_obat.update');
        Route::delete('/stok-obat/{id}', [ObatController::class, 'destroy'])->name('stok_obat.destroy');

    });