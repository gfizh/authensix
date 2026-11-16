<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SuperAdminTransactionController extends Controller
{
    // Melihat seluruh transaksi
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => [
                'nullable',
                Rule::in([
                    'pending',
                    'diproses',
                    'dikirim',
                    'selesai',
                    'dibatalkan',
                ]),
            ],
        ]);

        $pesanan = Pesanan::with([
            'user',
            'alamat',
            'items.produk',
            'pembayaran',
            'invoice',
            'pengiriman',
        ])
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_pesanan', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where(
                                'nama_lengkap',
                                'like',
                                "%{$search}%"
                            );
                        });
                });
            })
            ->when($validated['status'] ?? null, function ($query, $status) {
                $query->where('status_pemesanan', $status);
            })
            ->orderByDesc('tanggal_pesanan')
            ->paginate(15);

        return response()->json($pesanan);
    }

    // Melihat detail satu transaksi
    public function show(Pesanan $pesanan)
    {
        $pesanan->load([
            'user',
            'alamat',
            'items.produk',
            'pembayaran',
            'invoice',
            'pengiriman',
        ]);

        return response()->json([
            'data' => $pesanan,
        ]);
    }
}