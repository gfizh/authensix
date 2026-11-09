<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    /**
     * Menampilkan seluruh data pembayaran.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => [
                'nullable',
                Rule::in(['pending', 'dibayar', 'gagal', 'expired']),
            ],
        ]);

        $query = Pembayaran::with([
            'pesanan.user'
        ]);

        // Pencarian pembayaran
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('metode_pembayaran', 'like', "%{$search}%")
                    ->orWhere('kode_transaksi_gateway', 'like', "%{$search}%")
                    ->orWhereHas('pesanan', function ($pesananQuery) use ($search) {
                        $pesananQuery->where(
                            'nomor_pesanan',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('nama_lengkap', 'like', "%{$search}%")
                                ->orWhere('username', 'like', "%{$search}%");
                        });
                    });
            });
        }

        // Filter berdasarkan status pembayaran
        if (!empty($filters['status'])) {
            $query->where('status_pembayaran', $filters['status']);
        }

        $pembayaran = $query
            ->orderByDesc('id_pembayaran')
            ->paginate(10)
            ->withQueryString();

        return view('admin.payments.index', compact(
            'pembayaran',
            'filters'
        ));
    }

    /**
     * Menampilkan detail pembayaran.
     */
    public function show(Pembayaran $pembayaran): View
    {
        $pembayaran->load([
            'pesanan.user',
            'pesanan.alamat',
            'pesanan.items.produk',
        ]);

        return view('admin.payments.show', compact('pembayaran'));
    }

    /**
     * Mengubah status pembayaran.
     */
    public function updateStatus(
        Request $request,
        Pembayaran $pembayaran
    ): RedirectResponse {

        $data = $request->validate([
            'status_pembayaran' => [
                'required',
                Rule::in(['pending', 'dibayar', 'gagal', 'expired']),
            ],
        ]);

        DB::transaction(function () use ($pembayaran, $data) {

            $payment = Pembayaran::query()
                ->lockForUpdate()
                ->findOrFail($pembayaran->id_pembayaran);

            // Pembayaran yang sudah dikonfirmasi tidak dapat dibatalkan
            // melalui perubahan status biasa.
            if (
                $payment->status_pembayaran === 'dibayar' &&
                $data['status_pembayaran'] !== 'dibayar'
            ) {
                throw ValidationException::withMessages([
                    'status_pembayaran' =>
                        'Pembayaran yang sudah dikonfirmasi tidak dapat diubah.',
                ]);
            }

            $payment->status_pembayaran = $data['status_pembayaran'];

            // Catat waktu ketika pembayaran dikonfirmasi.
            if ($data['status_pembayaran'] === 'dibayar') {
                $payment->tanggal_pembayaran = now();
            }

            $payment->save();
        });

        return redirect()
            ->route('admin.payments.show', $pembayaran->id_pembayaran)
            ->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
