<?php

namespace App\Http\Controllers\Api;

use App\Models\ItemKeranjang;
use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartItemController
{
    use ApiResponse;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_produk' => [
                'required',
                'integer',
                'exists:produk,id_produk'
            ],

            'jumlah' => [
                'required',
                'integer',
                'min:1'
            ],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'Validasi gagal',
                $validator->errors(),
                422
            );
        }

        $product = Produk::find(
            $request->id_produk
        );

        if (!$product || !$product->is_active) {
            return $this->errorResponse(
                'Produk tidak tersedia',
                null,
                404
            );
        }

        $cart = Keranjang::firstOrCreate([
            'id_user' =>
                $request->user()->id_user,
        ]);

        $item = ItemKeranjang::where(
            'id_keranjang',
            $cart->id_keranjang
        )
        ->where(
            'id_produk',
            $product->id_produk
        )
        ->first();

        $newQuantity =
            ($item?->jumlah ?? 0)
            + $request->jumlah;

        if ($newQuantity > $product->stok) {
            return $this->errorResponse(
                'Jumlah produk melebihi stok',
                [
                    'stok_tersedia' =>
                        $product->stok
                ],
                422
            );
        }

        if ($item) {

            $item->update([
                'jumlah' => $newQuantity,

                // Ambil harga terbaru
                'harga_satuan' =>
                    $product->harga,
            ]);

        } else {

            $item = ItemKeranjang::create([
                'id_keranjang' =>
                    $cart->id_keranjang,

                'id_produk' =>
                    $product->id_produk,

                'jumlah' =>
                    $request->jumlah,

                'harga_satuan' =>
                    $product->harga,
            ]);
        }

        return $this->successResponse(
            $item->fresh('produk'),
            'Produk berhasil ditambahkan ke keranjang',
            201
        );
    }

    public function update(
        Request $request,
        int $id
    ) {
        $validator = Validator::make(
            $request->all(),
            [
                'jumlah' => [
                    'required',
                    'integer',
                    'min:1'
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

        $item = ItemKeranjang::with('produk')
            ->where(
                'id_item_keranjang',
                $id
            )
            ->whereHas(
                'keranjang',
                function ($query) use ($request) {
                    $query->where(
                        'id_user',
                        $request->user()->id_user
                    );
                }
            )
            ->first();

        if (!$item) {
            return $this->errorResponse(
                'Item keranjang tidak ditemukan',
                null,
                404
            );
        }

        if (
            $request->jumlah >
            $item->produk->stok
        ) {
            return $this->errorResponse(
                'Jumlah melebihi stok',
                [
                    'stok_tersedia' =>
                        $item->produk->stok
                ],
                422
            );
        }

        $item->update([
            'jumlah' =>
                $request->jumlah,

            'harga_satuan' =>
                $item->produk->harga,
        ]);

        return $this->successResponse(
            $item->fresh('produk'),
            'Jumlah item berhasil diperbarui'
        );
    }

    public function destroy(
        Request $request,
        int $id
    ) {
        $item = ItemKeranjang::where(
            'id_item_keranjang',
            $id
        )
        ->whereHas(
            'keranjang',
            function ($query) use ($request) {
                $query->where(
                    'id_user',
                    $request->user()->id_user
                );
            }
        )
        ->first();

        if (!$item) {
            return $this->errorResponse(
                'Item keranjang tidak ditemukan',
                null,
                404
            );
        }

        $item->delete();

        return $this->successResponse(
            null,
            'Item berhasil dihapus dari keranjang'
        );
    }
}