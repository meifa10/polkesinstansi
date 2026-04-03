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

        // ✅ DASHBOARD (PASTI KE CONTROLLER)
        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');

        Route::get('/pemeriksaan', [PemeriksaanController::class, 'index'])
            ->name('pemeriksaan');

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

        // 🔹 REKAM MEDIS
        Route::get('/rekam-medis', [DokterPemeriksaan::class, 'rekamMedis'])
            ->name('rekammedis');

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

                // 📋 LIST JADWAL
                Route::get('/', 'index')->name('jadwal_dokter');

                // ➕ TAMBAH JADWAL
                Route::post('/', 'store')->name('jadwal_dokter.store');

                // ✏️ UPDATE JADWAL
                Route::put('/{id}', 'update')->name('jadwal_dokter.update');

                // 🔁 AKTIF / NONAKTIF
                Route::post('/{id}/toggle', 'toggle')->name('jadwal_dokter.toggle');

                // 🗑️ HAPUS
                Route::delete('/{id}', 'destroy')->name('jadwal_dokter.destroy');
            });
    });


// pembayaran
Route::middleware(['auth','role:admin'])
->prefix('admin')
->group(function () {

    Route::get('/pembayaran', [PembayaranController::class,'index'])
        ->name('admin.pembayaran');

    Route::get('/pembayaran/create/{pendaftaran}',
        [PembayaranController::class,'create'])
        ->name('admin.pembayaran.create');

    Route::post('/pembayaran',
        [PembayaranController::class,'store'])
        ->name('admin.pembayaran.store');

    Route::post('/pembayaran/{pembayaran}/lunasi',
        [PembayaranController::class,'lunasi'])
        ->name('admin.pembayaran.lunasi');
});

// laporan 
Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('admin.laporan');

        Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])
            ->name('admin.laporan.pdf');
    });
