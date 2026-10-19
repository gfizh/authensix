<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{
     protected $table = 'item_pesanan';
    protected $primaryKey = 'id_item_pesanan';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'id_produk',
        'jumlah',
        'harga_satuan',
        'subtotal_item',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'harga_satuan' => 'decimal:2',
            'subtotal_item' => 'decimal:2',
        ];
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
