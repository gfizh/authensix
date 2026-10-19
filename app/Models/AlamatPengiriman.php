<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlamatPengiriman extends Model
{
    protected $table = 'alamat_pengiriman';
    protected $primaryKey = 'id_alamat';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'label_alamat',
        'nama_penerima',
        'nomor_telepon',
        'alamat_lengkap',
        'kota',
        'provinsi',
        'kode_pos',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_alamat', 'id_alamat');
    }

}
