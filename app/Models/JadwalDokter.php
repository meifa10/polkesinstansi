<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class JadwalDokter extends Model
{
    protected $table = 'jadwal_dokter';

    protected $fillable = [
        'dokter_id',
        'poli',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }
}
