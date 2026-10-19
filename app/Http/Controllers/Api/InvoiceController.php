<?php

namespace App\Http\Controllers\Api;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class InvoiceController
{
    use ApiResponse;

    public function show(
        Request $request,
        int $orderId
    ) {
        $order = Pesanan::with('invoice')
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

        if (!$order->invoice) {
            return $this->errorResponse(
                'Invoice belum tersedia',
                null,
                404
            );
        }

        return $this->successResponse(
            $order->invoice,
            'Invoice berhasil diambil'
        );
    }
}