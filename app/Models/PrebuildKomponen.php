<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrebuildKomponen extends Model
{
    protected $table = 'prebuild_komponen';
    protected $primaryKey = 'id_prebuild_komponen';
    public $timestamps = false;

    protected $fillable = [
        'id_produk_parent',
        'id_produk_child',
        'jumlah',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(
            Produk::class,
            'id_produk_parent',
            'id_produk'
        );
    }

    public function child()
    {
        return $this->belongsTo(
            Produk::class,
            'id_produk_child',
            'id_produk'
        );
    }
}
