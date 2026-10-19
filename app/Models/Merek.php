<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merek extends Model
{
    protected $table = 'merek';
    protected $primaryKey = 'id_merek';
    public $timestamps = false;

    protected $fillable = [
        'nama_merek',
        'logo_url',
    ];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'id_merek', 'id_merek');
    }
}
