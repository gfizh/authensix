<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpesifikasiProduk extends Model
{
    protected $table = 'spesifikasi_produk';
    protected $primaryKey = 'id_spesifikasi';
    public $timestamps = false;

    protected $fillable = [
        'id_produk',
        'nama_atribut',
        'nilai_atribut',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
