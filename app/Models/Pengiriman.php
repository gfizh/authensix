<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
     protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'kurir',
        'layanan',
        'nomor_resi',
        'status_pengiriman',
        'tanggal_pengiriman',
        'tanggal_diterima',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengiriman' => 'datetime',
            'tanggal_diterima' => 'datetime',
        ];
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
