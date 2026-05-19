<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Dokter\DashboardController as DokterDashboard;
use App\Http\Controllers\Admin\PemeriksaanController as AdminPemeriksaan;
use App\Http\Controllers\Dokter\PemeriksaanController as DokterPemeriksaan;
use App\Http\Controllers\Admin\DataPasienController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PemeriksaanController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\PemeriksaanAwalController;
use App\Http\Controllers\Petugas\PendaftaranController as PetugasPendaftaranController;
use App\Http\Controllers\Petugas\ObatController;
use App\Http\Controllers\Dokter\RekamMedisController;



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
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('instansi.login.post');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('instansi.logout');

Route::post('/register-dokter', [AuthController::class, 'register'])
    ->name('instansi.register');


/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/


Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.') 
    ->group(function () {

   
        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard'); 
      
        Route::get('/pemeriksaan', [PemeriksaanController::class, 'index'])
            ->name('pemeriksaan'); 
     
        Route::get('/pemeriksaan/{id}', [PemeriksaanController::class, 'show'])
            ->name('pemeriksaan.show'); 

        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan'); 
            
    });


/*
|--------------------------------------------------------------------------
| DASHBOARD DOKTER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->get('/dokter/dashboard', [DokterDashboard::class, 'index'])
    ->name('dokter.dashboard');

use App\Http\Controllers\Dokter\DashboardController;

Route::middleware(['auth', 'role:dokter'])
    ->prefix('dokter')
    ->name('dokter.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        });

Route::middleware(['auth', 'role:dokter'])
    ->prefix('dokter')
    ->name('dokter.')
    ->group(function () {

        Route::get('/pemeriksaan/{id}', [DokterPemeriksaan::class, 'show'])
            ->name('pemeriksaan.show');

        Route::post('/pemeriksaan/{id}', [DokterPemeriksaan::class, 'store'])
            ->name('pemeriksaan.store');
    });

Route::middleware(['auth', 'role:dokter'])
    ->prefix('dokter')
    ->name('dokter.')
    ->group(function () {

        Route::get('/dashboard', [DokterDashboard::class, 'index'])
            ->name('dashboard');

        // 🔹 DAFTAR PASIEN YANG AKAN DIPERIKSA
        Route::get('/pasien', [DokterPemeriksaan::class, 'index'])
            ->name('pasien');

        // 🔹 FORM PEMERIKSAAN
        Route::get('/pemeriksaan/{id}', [DokterPemeriksaan::class, 'show'])
            ->name('pemeriksaan.show');

        Route::post('/pemeriksaan/{id}', [DokterPemeriksaan::class, 'store'])
            ->name('pemeriksaan.store');

        // // 🔹 REKAM MEDIS
        // Route::get('/rekam-medis', [DokterPemeriksaan::class, 'rekamMedis'])
        //     ->name('rekammedis');

        Route::get('/rekam-medis', [RekamMedisController::class, 'index'])->name('rekammedis');
        Route::get('/rekam-medis/riwayat/{pendaftaran_id}', [RekamMedisController::class, 'riwayat'])->name('rekammedis.riwayat');

        // 🔹 PROFIL
        Route::view('/profil', 'dokter.profil')
            ->name('profil');
    });

/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\PendaftaranController;

Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/pendaftaran', [PendaftaranController::class, 'index'])
            ->name('admin.pendaftaran');

        Route::post('/pendaftaran/{id}/status', [PendaftaranController::class, 'updateStatus'])
            ->name('admin.pendaftaran.status');
    });

// GROUP ADMIN
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Route::get('/dashboard', function () {
        //     return view('admin.dashboard');
        // })->name('dashboard');

        Route::get('/pendaftaran', [PendaftaranController::class, 'index'])
            ->name('pendaftaran.index');

        Route::post('/pendaftaran/{id}/status', [PendaftaranController::class, 'updateStatus'])
            ->name('pendaftaran.status');
    });

// data pasien
Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/data-pasien', [DataPasienController::class, 'index'])
            ->name('data_pasien.index');

        Route::get('/data-pasien/{no_identitas}', [DataPasienController::class, 'detail'])
            ->name('data_pasien.detail');

});

// jadwal dokter
use App\Http\Controllers\Admin\JadwalDokterController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::controller(JadwalDokterController::class)
            ->prefix('jadwal-dokter')
            ->group(function () {

                Route::get('/', 'index')->name('jadwal_dokter');
                Route::post('/', 'store')->name('jadwal_dokter.store');
                Route::put('/{id}', 'update')->name('jadwal_dokter.update');
                Route::post('/{id}/toggle', 'toggle')->name('jadwal_dokter.toggle');
                Route::delete('/{id}', 'destroy')->name('jadwal_dokter.destroy');
            });
    });


Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->as('admin.') 
    ->group(function () {

        // 1. Halaman Utama Riwayat Transaksi Pasien
        Route::get('/pembayaran', [PembayaranController::class, 'index'])
            ->name('pembayaran'); // Menjadi: admin.pembayaran

        // 2. Halaman Rincian Detail Transaksi & Komponen Biaya
        Route::get('/pembayaran/detail/{id}', [PembayaranController::class, 'show'])
            ->name('pembayaran.show'); // Menjadi: admin.pembayaran.show

        // 3. Proses Validasi Pelunasan Kasir
        Route::post('/pembayaran/lunasi/{id}', [PembayaranController::class, 'lunasi'])
            ->name('pembayaran.lunasi'); // Menjadi: admin.pembayaran.lunasi

        // 4. Cetak / Print Struk Laporan Pembayaran Pasien
        Route::get('/pembayaran/print/{id}', [PembayaranController::class, 'printStruk'])
            ->name('pembayaran.print'); // Menjadi: admin.pembayaran.print

    });

// laporan 
Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('admin.laporan');

        // Tambahkan /{bulan}/{tahun} agar data dari tombol bisa masuk ke Controller

        Route::get('/admin/laporan/pdf/{bulan}/{tahun}', [LaporanController::class, 'exportPdf'])
            ->name('admin.laporan.pdf');
    });


// petugas
Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        Route::get('/dashboard',
            [PetugasDashboardController::class, 'index'])
            ->name('dashboard');

        // DATA PENDAFTARAN
        Route::get('/pendaftaran',
            [PetugasPendaftaranController::class, 'index'])
            ->name('pendaftaran.index');

        // PEMERIKSAAN AWAL
        Route::get('/pemeriksaan-awal',
            [PemeriksaanAwalController::class, 'index'])
            ->name('pemeriksaan_awal.index');

        Route::get('/pemeriksaan-awal/{id}',
            [PemeriksaanAwalController::class, 'edit'])
            ->name('pemeriksaan_awal.edit');

        Route::put('/pemeriksaan-awal/{id}',
            [PemeriksaanAwalController::class, 'update'])
            ->name('pemeriksaan_awal.update');

        // STOK OBAT
        Route::get('/stok-obat', function () {
            return view('petugas.stok_obat.index');
        })->name('stok_obat.index');

        Route::get('/stok-obat',
            [ObatController::class, 'index'])
            ->name('stok_obat.index');

        Route::post('/stok-obat',
            [ObatController::class, 'store'])
            ->name('stok_obat.store');

        Route::put('/stok-obat/{id}',
            [ObatController::class, 'update'])
            ->name('stok_obat.update');

        Route::delete('/stok-obat/{id}',
            [ObatController::class, 'destroy'])
            ->name('stok_obat.destroy');

});