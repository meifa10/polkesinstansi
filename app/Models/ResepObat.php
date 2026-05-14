<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepObat extends Model
{
    protected $table = 'resep_obat';

    protected $fillable = [
        'rekam_medis_id',
        'obat_id',
        'qty',
        'aturan_minum',
        'subtotal'
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}