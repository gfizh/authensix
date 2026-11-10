<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderHistoryController extends Controller
{
    /**
     * Menampilkan daftar riwayat pesanan milik kostumer yang sedang login.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()?->isAdmin()) {
            return redirect()->route('admin.manajemen_produk');
        }

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => [
                'nullable',
                Rule::in(['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan']),
            ],
        ]);

        $userId = (int) $request->user()->id_user;

        $countsByStatus = Pesanan::query()
            ->where('id_user', $userId)
            ->select('status_pemesanan', DB::raw('COUNT(*) as total'))
            ->groupBy('status_pemesanan')
            ->pluck('total', 'status_pemesanan');

        $orderStats = [
            'total' => (int) $countsByStatus->sum(),
            'pending' => (int) ($countsByStatus['pending'] ?? 0),
            'diproses' => (int) ($countsByStatus['diproses'] ?? 0),
            'dikirim' => (int) ($countsByStatus['dikirim'] ?? 0),
            'selesai' => (int) ($countsByStatus['selesai'] ?? 0),
            'dibatalkan' => (int) ($countsByStatus['dibatalkan'] ?? 0),
        ];

        $orders = Pesanan::query()
            ->with([
                'items.produk',
                'alamat',
                'pembayaran',
                'invoice',
                'pengiriman',
            ])
            ->where('id_user', $userId)
            ->when(
                filled($filters['search'] ?? null),
                function ($query) use ($filters): void {
                    $search = '%'.$filters['search'].'%';

                    $query->where(function ($subQuery) use ($search): void {
                        $subQuery
                            ->where('nomor_pesanan', 'like', $search)
                            ->orWhereHas('items.produk', function ($productQuery) use ($search): void {
                                $productQuery->where('nama_produk', 'like', $search);
                            });
                    });
                }
            )
            ->when(
                filled($filters['status'] ?? null),
                fn ($query) => $query->where('status_pemesanan', $filters['status'])
            )
            ->orderByDesc('tanggal_pesanan')
            ->orderByDesc('id_pesanan')
            ->paginate(10)
            ->withQueryString();

        return view('users.riwayat-pesanan', compact('orders', 'orderStats', 'filters'));
    }

    /**
     * Membatalkan pesanan oleh kostumer selama belum dikirim.
     */
    public function cancel(Request $request, Pesanan $pesanan): RedirectResponse
    {
        if ((int) $pesanan->id_user !== (int) $request->user()->id_user) {
            abort(403, 'Anda tidak memiliki akses untuk membatalkan pesanan ini.');
        }

        if (! $pesanan->cancelAndRestoreStock()) {
            return back()->with(
                'error',
                "Pesanan {$pesanan->nomor_pesanan} tidak dapat dibatalkan karena statusnya saat ini adalah {$pesanan->status_pemesanan}."
            );
        }

        return back()->with(
            'success',
            "Pesanan {$pesanan->nomor_pesanan} berhasil dibatalkan."
        );
    }
}
