<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    // Relasi ke pengguna
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    // Relasi ke alamat pengiriman
    public function alamat()
    {
        return $this->belongsTo(
            AlamatPengiriman::class,
            'id_alamat',
            'id_alamat'
        );
    }

    // Relasi ke item pesanan
    public function items()
    {
        return $this->hasMany(
            ItemPesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    // Relasi ke pembayaran
    public function pembayaran()
    {
        return $this->hasOne(
            Pembayaran::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    // Relasi ke invoice
    public function invoice()
    {
        return $this->hasOne(
            Invoice::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    // Relasi ke pengiriman
    public function pengiriman()
    {
        return $this->hasOne(
            Pengiriman::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function produk()
    {
        return $this->belongsTo(
            Produk::class,
            'id_produk',
            'id_produk'
        );
    }

    public function pesanan()
    {
        return $this->belongsTo(
            Pesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    /**
     * Menentukan apakah pesanan masih dapat dibatalkan.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status_pemesanan, ['pending', 'diproses'], true);
    }

    /**
     * Membatalkan pesanan serta mengembalikan stok dan jumlah terjual produk.
     */
    public function cancelAndRestoreStock(): bool
    {
        return DB::transaction(function (): bool {
            $lockedOrder = self::query()
                ->with(['items.produk', 'pembayaran'])
                ->lockForUpdate()
                ->findOrFail($this->id_pesanan);

            if (! $lockedOrder->canBeCancelled()) {
                return false;
            }

            foreach ($lockedOrder->items as $item) {
                if ($item->produk) {
                    $item->produk->increment('stok', $item->jumlah);

                    if ($item->produk->jumlah_terjual >= $item->jumlah) {
                        $item->produk->decrement('jumlah_terjual', $item->jumlah);
                    }
                }
            }

            if ($lockedOrder->pembayaran && $lockedOrder->pembayaran->status_pembayaran === 'pending') {
                $lockedOrder->pembayaran->update([
                    'status_pembayaran' => 'gagal',
                ]);
            }

            $lockedOrder->status_pemesanan = 'dibatalkan';
            $lockedOrder->save();

            $this->refresh();

            return true;
        });
    }
}
