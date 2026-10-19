<?php

namespace App\Http\Controllers\Api;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController
{
    use ApiResponse;

    public function store(
        Request $request,
        int $orderId
    ) {
        $order = Pesanan::with('pembayaran')
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

        if ($order->pembayaran) {
            return $this->errorResponse(
                'Pembayaran untuk pesanan ini sudah dibuat',
                null,
                409
            );
        }

        if ($order->status_pemesanan === 'dibatalkan') {
            return $this->errorResponse(
                'Pesanan sudah dibatalkan',
                null,
                409
            );
        }

        $validator = Validator::make(
            $request->all(),
            [
                'metode_pembayaran' => [
                    'required',
                    'string',
                    'max:50',
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

        $payment = Pembayaran::create([
            'id_pesanan' =>
                $order->id_pesanan,

            'metode_pembayaran' =>
                $request->metode_pembayaran,

            'status_pembayaran' =>
                'pending',

            'jumlah_bayar' =>
                $order->total_pembayaran,

            'kode_transaksi_gateway' =>
                'TRX-' .
                now()->format('YmdHis') .
                '-' .
                random_int(1000, 9999),

            'tanggal_pembayaran' =>
                null,

            'batas_waktu_pembayaran' =>
                now()->addHours(24),
        ]);

        return $this->successResponse(
            $payment,
            'Data pembayaran berhasil dibuat',
            201
        );
    }
}