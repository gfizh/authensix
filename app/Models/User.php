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
        'email',
        'password_hash',
        'initial_password',
        'role',
        'is_active',
        'must_change_password',
    ];

    protected $hidden = [
        'password_hash',
        'initial_password',
    ];

    public function getQuickLoginPasswordAttribute(): string
    {
        if (! empty($this->initial_password)) {
            return $this->initial_password;
        }

        if ($this->username === 'admin') {
            return 'admin123';
        }

        if ($this->username === 'guruhhhh' || $this->role === 'superadmin') {
            return 'superadmin123';
        }

        if ($this->role === 'admin') {
            return 'admin123';
        }

        return 'password';
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function alamatPengiriman()
    {
        return $this->hasMany(
            AlamatPengiriman::class,
            'id_user',
            'id_user'
        );
    }

    public function keranjang()
    {
        return $this->hasOne(
            Keranjang::class,
            'id_user',
            'id_user'
        );
    }

    public function pesanan()
    {
        return $this->hasMany(
            Pesanan::class,
            'id_user',
            'id_user'
        );
    }

    public function ulasan()
    {
        return $this->hasMany(
            Ulasan::class,
            'id_user',
            'id_user'
        );
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
