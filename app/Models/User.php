<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama_lengkap',
        'nomor_telepon',
        'username',
        'password_hash',
        'role',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function alamatPengiriman()
    {
        return $this->hasMany(AlamatPengiriman::class, 'id_user', 'id_user');
    }

    public function keranjang()
    {
        return $this->hasOne(Keranjang::class, 'id_user', 'id_user');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_user', 'id_user');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_user', 'id_user');
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
