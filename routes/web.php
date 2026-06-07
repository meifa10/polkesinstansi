<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Controller Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PemeriksaanController as AdminPemeriksaan;
use App\Http\Controllers\Admin\DataPasienController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PendaftaranController as AdminPendaftaranController;
use App\Http\Controllers\Admin\JadwalDokterController;

// Controller Petugas
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\PemeriksaanAwalController;
use App\Http\Controllers\Petugas\PendaftaranController as PetugasPendaftaranController;
use App\Http\Controllers\Petugas\ObatController as PetugasObatController;

// Controller Dokter
use App\Http\Controllers\Dokter\DashboardController as DokterDashboardController;
use App\Http\Controllers\Dokter\PemeriksaanController as DokterPemeriksaan;
use App\Http\Controllers\Dokter\RekamMedisController;
use App\Http\Controllers\Dokter\ObatController as DokterObatController;

/*
|--------------------------------------------------------------------------
| HALAMAN AWAL & AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('instansi.login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('instansi.logout');
Route::post('/register-dokter', [AuthController::class, 'register'])->name('instansi.register');


/*
|--------------------------------------------------------------------------
| PANEL ADMIN (SEMUA ROUTE ADMIN DIJADIKAN SATU GRUP)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard'); 
    
    // Pemeriksaan
    Route::get('/pemeriksaan', [AdminPemeriksaan::class, 'index'])->name('pemeriksaan'); 
    Route::get('/pemeriksaan/{id}', [AdminPemeriksaan::class, 'show'])->name('pemeriksaan.show'); 

    // Pendaftaran Pasien
    Route::get('/pendaftaran', [AdminPendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::post('/pendaftaran/{id}/status', [AdminPendaftaranController::class, 'updateStatus'])->name('pendaftaran.status');

    // Data Pasien
    Route::get('/data-pasien', [DataPasienController::class, 'index'])->name('data_pasien.index');
    Route::get('/data-pasien/{no_identitas}', [DataPasienController::class, 'detail'])->name('data_pasien.detail');

    // Jadwal Dokter
    Route::controller(JadwalDokterController::class)->prefix('jadwal-dokter')->group(function () {
        Route::get('/', 'index')->name('jadwal_dokter');
        Route::post('/', 'store')->name('jadwal_dokter.store');
        Route::put('/{id}', 'update')->name('jadwal_dokter.update');
        Route::post('/{id}/toggle', 'toggle')->name('jadwal_dokter.toggle');
        Route::delete('/{id}', 'destroy')->name('jadwal_dokter.destroy');
    });

    // Kasir & Pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran'); 
    Route::get('/pembayaran/detail/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show'); 
    Route::post('/pembayaran/lunasi/{id}', [PembayaranController::class, 'lunasi'])->name('pembayaran.lunasi'); 
    Route::get('/pembayaran/print/{id}', [PembayaranController::class, 'printStruk'])->name('pembayaran.print'); 

    // Laporan Bulanan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/pdf/{bulan}/{tahun}', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
});


/*
|--------------------------------------------------------------------------
| PANEL DOKTER (SINKRON DATA OBAT REAL-TIME)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DokterDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Pasien & Tindakan Pemeriksaan
    Route::get('/pasien', [DokterPemeriksaan::class, 'index'])->name('pasien');
    Route::get('/pemeriksaan/{id}', [DokterPemeriksaan::class, 'show'])->name('pemeriksaan.show');
    Route::post('/pemeriksaan/{id}', [DokterPemeriksaan::class, 'store'])->name('pemeriksaan.store');

    // Rekam Medis Pasien
    Route::get('/rekam-medis', [RekamMedisController::class, 'index'])->name('rekammedis');
    Route::get('/rekam-medis/riwayat/{pendaftaran_id}', [RekamMedisController::class, 'riwayat'])->name('rekammedis.riwayat');

    // 🔹 ROUTE BARU: DATA STOK OBAT APOTEK REAL-TIME
    Route::get('/stok-obat', [DokterObatController::class, 'index'])->name('data_obat');

    // Profil Akun Dokter
    Route::view('/profil', 'dokter.profil')->name('profil');
});


/*
|--------------------------------------------------------------------------
| PANEL PETUGAS / FARMASI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');

    // Data Loket Pendaftaran
    Route::get('/pendaftaran', [PetugasPendaftaranController::class, 'index'])->name('pendaftaran.index');

    // Pemeriksaan Tanda Vital Awal
    Route::get('/pemeriksaan-awal', [PemeriksaanAwalController::class, 'index'])->name('pemeriksaan_awal.index');
    Route::get('/pemeriksaan-awal/{id}', [PemeriksaanAwalController::class, 'edit'])->name('pemeriksaan_awal.edit');
    Route::put('/pemeriksaan-awal/{id}', [PemeriksaanAwalController::class, 'update'])->name('pemeriksaan_awal.update');

    // Gudang Stok Obat Farmasi (Petugas berwenang penuh CRUD)
    Route::get('/stok-obat', [PetugasObatController::class, 'index'])->name('stok_obat.index');
    Route::post('/stok-obat', [PetugasObatController::class, 'store'])->name('stok_obat.store');
    Route::put('/stok-obat/{id}', [PetugasObatController::class, 'update'])->name('stok_obat.update');
    Route::delete('/stok-obat/{id}', [PetugasObatController::class, 'destroy'])->name('stok_obat.destroy');
});