<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'id_kategori',
        'id_merek',
        'sku',
        'nama_produk',
        'tipe_produk',
        'harga',
        'stok',
        'jumlah_terjual',
        'kondisi',
        'masa_garansi_bulan',
        'deskripsi',
        'gambar_path',
        'rating_rata_rata',
        'is_active',
    ];

    // URL gambar ditambahkan otomatis ke hasil JSON API.
    protected $appends = ['gambar_url'];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'stok' => 'integer',
            'jumlah_terjual' => 'integer',
            'masa_garansi_bulan' => 'integer',
            'rating_rata_rata' => 'decimal:2',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Menghasilkan URL publik dari path foto lokal.
     */
    public function getGambarUrlAttribute(): ?string
    {
        if (empty($this->gambar_path)) {
            return null;
        }

        return asset('storage/' . $this->gambar_path);
    }

    public function kategori()
    {
        return $this->belongsTo(
            Kategori::class,
            'id_kategori',
            'id_kategori'
        );
    }

    public function merek()
    {
        return $this->belongsTo(
            Merek::class,
            'id_merek',
            'id_merek'
        );
    }

    public function spesifikasi()
    {
        return $this->hasMany(
            SpesifikasiProduk::class,
            'id_produk',
            'id_produk'
        );
    }

    public function itemKeranjang()
    {
        return $this->hasMany(
            ItemKeranjang::class,
            'id_produk',
            'id_produk'
        );
    }

    public function itemPesanan()
    {
        return $this->hasMany(
            ItemPesanan::class,
            'id_produk',
            'id_produk'
        );
    }

    public function ulasan()
    {
        return $this->hasMany(
            Ulasan::class,
            'id_produk',
            'id_produk'
        );
    }

    public function prebuildParent()
    {
        return $this->hasMany(
            PrebuildKomponen::class,
            'id_produk_parent',
            'id_produk'
        );
    }

    public function prebuildChild()
    {
        return $this->hasMany(
            PrebuildKomponen::class,
            'id_produk_child',
            'id_produk'
        );
    }
}
