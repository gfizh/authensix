<?php

namespace App\Http\Controllers\Api;

use App\Models\Keranjang;
use Illuminate\Http\Request;

class CartController
{
    use ApiResponse;

    public function show(Request $request)
    {
        $cart = Keranjang::firstOrCreate([
            'id_user' => $request->user()->id_user,
        ]);

        $cart->load([
            'items.produk.kategori',
            'items.produk.merek',
        ]);

        $items = $cart->items->map(function ($item) {
            return [
                'id_item_keranjang' =>
                    $item->id_item_keranjang,

                'jumlah' =>
                    $item->jumlah,

                'harga_satuan' =>
                    $item->harga_satuan,

                'subtotal' =>
                    $item->jumlah *
                    (float) $item->harga_satuan,

                'produk' =>
                    $item->produk,
            ];
        });

        return $this->successResponse([
            'id_keranjang' =>
                $cart->id_keranjang,

            'items' =>
                $items,

            'subtotal' =>
                $items->sum('subtotal'),
        ], 'Data keranjang berhasil diambil');
    }
}