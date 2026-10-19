<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $timestamps = false;

    protected $fillable = [
        'nomor_pesanan',
        'id_user',
        'id_alamat',
        'subtotal',
        'biaya_pengiriman',
        'pajak',
        'total_pembayaran',
        'status_pemesanan',
        'tanggal_pesanan',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'biaya_pengiriman' => 'decimal:2',
            'pajak' => 'decimal:2',
            'total_pembayaran' => 'decimal:2',
            'tanggal_pesanan' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function alamat()
    {
        return $this->belongsTo(
            AlamatPengiriman::class,
            'id_alamat',
            'id_alamat'
        );
    }

    public function items()
    {
        return $this->hasMany(ItemPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id_pesanan', 'id_pesanan');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_pesanan', 'id_pesanan');
    }
}
