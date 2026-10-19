<?php

namespace App\Http\Controllers\Api;

use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\ItemPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController
{
    use ApiResponse;

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_alamat' => [
                    'required',
                    'integer',
                    'exists:alamat_pengiriman,id_alamat',
                ],

                'biaya_pengiriman' => [
                    'required',
                    'numeric',
                    'min:0',
                ],

                'pajak' => [
                    'required',
                    'numeric',
                    'min:0',
                ],
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse(
                'Validasi gagal',
                $validator->errors(),
                422
            );
        }

        $user = $request->user();

        // Pastikan alamat milik user
        $addressExists = $user
            ->alamatPengiriman()
            ->where(
                'id_alamat',
                $request->id_alamat
            )
            ->exists();

        if (!$addressExists) {
            return $this->errorResponse(
                'Alamat bukan milik pengguna',
                null,
                403
            );
        }

        // Ambil keranjang
        $cart = Keranjang::with(
            'items.produk'
        )
        ->where(
            'id_user',
            $user->id_user
        )
        ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return $this->errorResponse(
                'Keranjang kosong',
                null,
                422
            );
        }

        // Cek semua produk
        foreach ($cart->items as $item) {

            if (
                !$item->produk ||
                !$item->produk->is_active
            ) {
                return $this->errorResponse(
                    'Ada produk yang sudah tidak tersedia',
                    [
                        'id_produk' =>
                            $item->id_produk
                    ],
                    422
                );
            }

            if (
                $item->jumlah >
                $item->produk->stok
            ) {
                return $this->errorResponse(
                    'Stok produk tidak mencukupi',
                    [
                        'id_produk' =>
                            $item->id_produk,

                        'nama_produk' =>
                            $item->produk->nama_produk,

                        'stok_tersedia' =>
                            $item->produk->stok,
                    ],
                    422
                );
            }
        }

        try {

            $order = DB::transaction(
                function () use (
                    $cart,
                    $request,
                    $user
                ) {

                    $subtotal = $cart->items->sum(
                        function ($item) {
                            return
                                $item->jumlah *
                                (float)
                                $item->produk->harga;
                        }
                    );

                    $shipping =
                        (float)
                        $request->biaya_pengiriman;

                    $tax =
                        (float)
                        $request->pajak;

                    $total =
                        $subtotal +
                        $shipping +
                        $tax;

                    // Buat PESANAN
                    $order = Pesanan::create([
                        'nomor_pesanan' =>
                            'ORD-' .
                            now()->format('YmdHis') .
                            '-' .
                            random_int(1000, 9999),

                        'id_user' =>
                            $user->id_user,

                        'id_alamat' =>
                            $request->id_alamat,

                        'subtotal' =>
                            $subtotal,

                        'biaya_pengiriman' =>
                            $shipping,

                        'pajak' =>
                            $tax,

                        'total_pembayaran' =>
                            $total,

                        'status_pemesanan' =>
                            'pending',

                        'tanggal_pesanan' =>
                            now(),
                    ]);

                    // Pindahkan cart item → order item
                    foreach (
                        $cart->items
                        as $cartItem
                    ) {

                        $price =
                            (float)
                            $cartItem->produk->harga;

                        ItemPesanan::create([
                            'id_pesanan' =>
                                $order->id_pesanan,

                            'id_produk' =>
                                $cartItem->id_produk,

                            'jumlah' =>
                                $cartItem->jumlah,

                            'harga_satuan' =>
                                $price,

                            'subtotal_item' =>
                                $price *
                                $cartItem->jumlah,
                        ]);

                        // Kurangi stok
                        $cartItem->produk
                            ->decrement(
                                'stok',
                                $cartItem->jumlah
                            );

                        // Tambah jumlah terjual
                        $cartItem->produk
                            ->increment(
                                'jumlah_terjual',
                                $cartItem->jumlah
                            );
                    }

                    // Cart dikosongkan
                    $cart->items()->delete();

                    return $order;
                }
            );

            $order->load([
                'alamat',
                'items.produk',
                'pembayaran',
                'invoice',
                'pengiriman',
            ]);

            return $this->successResponse(
                $order,
                'Checkout berhasil',
                201
            );

        } catch (\Throwable $e) {

            return $this->errorResponse(
                'Checkout gagal',

                config('app.debug')
                    ? [
                        'detail' =>
                            $e->getMessage()
                    ]
                    : null,

                500
            );
        }
    }
}