<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    /**
     * =========================
     * MASS ASSIGNMENT
     * =========================
     */
    protected $fillable = [
        'pendaftaran_id',
        'total_biaya',
        'status',
        'metode',
        'payment_ref',
        'paid_by',
        'tanggal_bayar',
    ];

    /**
     * =========================
     * CASTING
     * =========================
     */
    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    /**
     * =========================
     * RELATIONSHIP
     * =========================
     */

    /**
     * Pembayaran milik satu pendaftaran / kunjungan
     */
    public function pendaftaran()
    {
        return $this->belongsTo(
            PendaftaranPoli::class,
            'pendaftaran_id'
        );
    }

    /**
     * =========================
     * HELPER / ACCESSOR
     * =========================
     */

    /**
     * Cek apakah pembayaran sudah lunas
     */
    public function isLunas(): bool
    {
        return $this->status === 'lunas';
    }

    /**
     * Format rupiah
     */
    public function getTotalBiayaRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->total_biaya, 0, ',', '.');
    }

    /**
     * Label status
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'lunas'       => 'LUNAS',
            'gagal'       => 'GAGAL',
            default       => 'BELUM LUNAS',
        };
    }

    /**
     * Label metode pembayaran
     */
    public function getMetodeLabelAttribute(): string
    {
        return strtoupper($this->metode ?? '-');
    }
}
