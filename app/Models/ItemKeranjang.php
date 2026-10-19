<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemKeranjang extends Model
{
    protected $table = 'item_keranjang';
    protected $primaryKey = 'id_item_keranjang';
    public $timestamps = false;

    protected $fillable = [
        'id_keranjang',
        'id_produk',
        'jumlah',
        'harga_satuan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'harga_satuan' => 'decimal:2',
        ];
    }

    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class, 'id_keranjang', 'id_keranjang');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function getSubtotalAttribute()
    {
        return $this->jumlah * $this->harga_satuan;
    }
}
