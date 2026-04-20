<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranPoli extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_poli';

    /**
     * Kolom yang boleh diisi (WAJIB SAMA DENGAN DB)
     */
    protected $fillable = [
        'jenis_pasien',      // jkn / umum
        'nama_pasien',
        'no_identitas',      // BPJS / KTP / RM
        'tanggal_lahir',
        'poli',
        'status',            // menunggu | diproses | selesai | ditolak
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * ======================
     * RELATIONSHIP
     * ======================
     */

    // 1️⃣ Pendaftaran → Rekam Medis (1 pendaftaran 1 hasil medis)
    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class, 'pendaftaran_id');
    }

    // 2️⃣ Pendaftaran → Pembayaran (PENTING untuk Laporan Pemasukan)
    // Relasi ini digunakan di Controller: with(['pembayaran'])
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pendaftaran_id');
    }

    // 3️⃣ Dokter yang memeriksa (via rekam medis)
    public function dokter()
    {
        return $this->hasOneThrough(
            User::class,
            RekamMedis::class,
            'pendaftaran_id', // FK di rekam_medis
            'id',             // PK di users
            'id',             // PK di pendaftaran_poli
            'dokter_id'       // FK di rekam_medis ke users
        );
    }

    // Jika Anda memiliki tabel 'pasiens' yang terpisah, aktifkan ini:
    /*
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }
    */

    /**
     * ======================
     * HELPER STATUS
     * ======================
     */

    public function isMenunggu()
    {
        return $this->status === 'menunggu';
    }

    public function isDiproses()
    {
        return $this->status === 'diproses';
    }

    public function isSelesai()
    {
        return $this->status === 'selesai';
    }
}