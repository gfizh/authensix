<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'metode_pembayaran',
        'status_pembayaran',
        'jumlah_bayar',
        'kode_transaksi_gateway',
        'tanggal_pembayaran',
        'batas_waktu_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_bayar' => 'decimal:2',
            'tanggal_pembayaran' => 'datetime',
            'batas_waktu_pembayaran' => 'datetime',
        ];
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
