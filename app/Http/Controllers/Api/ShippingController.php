<?php

namespace App\Http\Controllers\Api;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class ShippingController
{
    use ApiResponse;

    public function show(
        Request $request,
        int $orderId
    ) {
        $order = Pesanan::with('pengiriman')
            ->where(
                'id_pesanan',
                $orderId
            )
            ->where(
                'id_user',
                $request->user()->id_user
            )
            ->first();

        if (!$order) {
            return $this->errorResponse(
                'Pesanan tidak ditemukan',
                null,
                404
            );
        }

        if (!$order->pengiriman) {
            return $this->errorResponse(
                'Data pengiriman belum tersedia',
                null,
                404
            );
        }

        return $this->successResponse(
            $order->pengiriman,
            'Data pengiriman berhasil diambil'
        );
    }
}