<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;

class SuperAdminDashboardController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'message' => 'Dashboard SUPERADMIN.',

            'users' => [
                'total' => User::count(),
                'customer' => User::where('role', 'customer')->count(),
                'admin' => User::where('role', 'admin')->count(),
                'superadmin' => User::where('role', 'superadmin')->count(),
            ],

            'transactions' => [
                'total' => Pesanan::count(),
                'pending' => Pesanan::where(
                    'status_pemesanan',
                    'pending'
                )->count(),
                'diproses' => Pesanan::where(
                    'status_pemesanan',
                    'diproses'
                )->count(),
                'dikirim' => Pesanan::where(
                    'status_pemesanan',
                    'dikirim'
                )->count(),
                'selesai' => Pesanan::where(
                    'status_pemesanan',
                    'selesai'
                )->count(),
                'dibatalkan' => Pesanan::where(
                    'status_pemesanan',
                    'dibatalkan'
                )->count(),
            ],

            'payments' => [
                'total' => Pembayaran::count(),
                'dibayar' => Pembayaran::where(
                    'status_pembayaran',
                    'dibayar'
                )->count(),
                'pending' => Pembayaran::where(
                    'status_pembayaran',
                    'pending'
                )->count(),
                'gagal' => Pembayaran::where(
                    'status_pembayaran',
                    'gagal'
                )->count(),
                'expired' => Pembayaran::where(
                    'status_pembayaran',
                    'expired'
                )->count(),
            ],
        ]);
    }
}
