<?php

namespace App\Http\Controllers\Api;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class OrderController
{
    use ApiResponse;

    public function index(Request $request)
    {
        $orders = Pesanan::with([
            'items.produk',
            'alamat',
            'pembayaran',
            'invoice',
            'pengiriman',
        ])
        ->where(
            'id_user',
            $request->user()->id_user
        )
        ->latest('id_pesanan')
        ->paginate(
            min(
                max(
                    (int) $request->get(
                        'per_page',
                        10
                    ),
                    1
                ),
                50
            )
        );

        return $this->successResponse([
            'items' =>
                $orders->items(),

            'meta' => [
                'current_page' =>
                    $orders->currentPage(),

                'last_page' =>
                    $orders->lastPage(),

                'per_page' =>
                    $orders->perPage(),

                'total' =>
                    $orders->total(),
            ],
        ], 'Data pesanan berhasil diambil');
    }

    public function show(
        Request $request,
        int $id
    ) {
        $order = Pesanan::with([
            'alamat',
            'items.produk',
            'pembayaran',
            'invoice',
            'pengiriman',
        ])
        ->where(
            'id_user',
            $request->user()->id_user
        )
        ->where(
            'id_pesanan',
            $id
        )
        ->first();

        if (!$order) {
            return $this->errorResponse(
                'Pesanan tidak ditemukan',
                null,
                404
            );
        }

        return $this->successResponse(
            $order,
            'Detail pesanan berhasil diambil'
        );
    }
}