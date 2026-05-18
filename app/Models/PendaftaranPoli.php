<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranPoli extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_poli';

    protected $fillable = [
        'user_id',
        'jenis_pasien',
        'nama_pasien',
        'no_identitas',
        'tanggal_lahir',
        'poli',
        'dokter_id', 
        'nomor_antrian',
        'status',
        'token_akses'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * ==========================================
     * RELATIONSHIPS
     * ==========================================
     */

    // Langsung ke User karena dokter_id ada di tabel ini
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class, 'pendaftaran_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pendaftaran_id');
    }

    /**
     * ==========================================
     * HELPER METHODS
     * ==========================================
     */
    
    public function isMenunggu() 
    { 
        return $this->status === 'menunggu_petugas'; 
    }
    
    public function isSelesai() 
    { 
        return $this->status === 'selesai'; 
    }

    /**
     * ==========================================
     * LOCAL SCOPES (Untuk Kebutuhan Statistik/Chart)
     * ==========================================
     */

    public function scopePoliUmum($query)
    {
        return $query->where('poli', 'like', '%Umum%');
    }

    public function scopePoliGigi($query)
    {
        return $query->where('poli', 'like', '%Gigi%');
    }

    public function scopePoliKiaKb($query)
    {
        return $query->where(function($q) {
            $q->where('poli', 'like', '%KIA%')
              ->orWhere('poli', 'like', '%KB%');
        });
    }
}