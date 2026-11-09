<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Menampilkan seluruh pesanan.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
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

        $orders = Pesanan::query()
            ->with([
                'user',
                'alamatPengiriman',
                'pembayaran',
                'pengiriman',
            ])
            ->when(
                filled($filters['search'] ?? null),
                function ($query) use ($filters) {
                    $search = '%' . $filters['search'] . '%';

                    $query->where(function ($subQuery) use ($search) {
                        $subQuery
                            ->where('nomor_pesanan', 'like', $search)
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where(
                                    'nama_lengkap',
                                    'like',
                                    $search
                                );
                            });
                    });
                }
            )
            ->when(
                filled($filters['status'] ?? null),
                fn ($query) => $query->where(
                    'status_pemesanan',
                    $filters['status']
                )
            )
            ->orderByDesc('tanggal_pesanan')
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan.
     */
    public function show(Pesanan $pesanan): View
    {
        $pesanan->load([
            'user',
            'alamatPengiriman',
            'itemPesanan.produk',
            'pembayaran',
            'invoice',
            'pengiriman',
        ]);

        return view('admin.orders.show', compact('pesanan'));
    }

    /**
     * Memproses pesanan atau membatalkannya.
     */
    public function updateStatus(
        Request $request,
        Pesanan $pesanan
    ): RedirectResponse {

        $data = $request->validate([
            'status_pemesanan' => [
                'required',
                Rule::in([
                    'diproses',
                    'dibatalkan',
                ]),
            ],
        ]);

        // Hanya pesanan pending yang boleh diubah melalui method ini.
        if ($pesanan->status_pemesanan !== 'pending') {
            return back()->with(
                'error',
                'Status pesanan ini tidak dapat diubah melalui proses tersebut.'
            );
        }

        $isPaid = $pesanan->pembayaran()
            ->where('status_pembayaran', 'dibayar')
            ->exists();

        // Pesanan hanya boleh diproses jika pembayaran berhasil.
        if (
            $data['status_pemesanan'] === 'diproses'
            && !$isPaid
        ) {
            return back()->with(
                'error',
                'Pesanan belum memiliki pembayaran yang berhasil.'
            );
        }

        // Pesanan yang sudah dibayar tidak boleh langsung dibatalkan.
        // Pembatalan tersebut memerlukan proses refund tersendiri.
        if (
            $data['status_pemesanan'] === 'dibatalkan'
            && $isPaid
        ) {
            return back()->with(
                'error',
                'Pesanan sudah dibayar. Lakukan proses refund terlebih dahulu.'
            );
        }

        $pesanan->status_pemesanan = $data['status_pemesanan'];
        $pesanan->save();

        return back()->with(
            'success',
            'Status pesanan berhasil diperbarui.'
        );
    }

    /**
     * Membuat data pengiriman untuk pesanan.
     */
    public function storeShipment(
        Request $request,
        Pesanan $pesanan
    ): RedirectResponse {

        $data = $request->validate([
            'kurir' => [
                'required',
                'string',
                'max:255',
            ],

            'layanan' => [
                'required',
                'string',
                'max:255',
            ],

            'nomor_resi' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        if ($pesanan->status_pemesanan !== 'diproses') {
            return back()->with(
                'error',
                'Pesanan harus berstatus diproses sebelum dibuatkan pengiriman.'
            );
        }

        $isPaid = $pesanan->pembayaran()
            ->where('status_pembayaran', 'dibayar')
            ->exists();

        if (!$isPaid) {
            return back()->with(
                'error',
                'Pesanan belum dibayar.'
            );
        }

        if ($pesanan->pengiriman()->exists()) {
            return back()->with(
                'error',
                'Data pengiriman untuk pesanan ini sudah tersedia.'
            );
        }

        DB::transaction(function () use ($pesanan, $data) {
            $pengiriman = new Pengiriman();

            $pengiriman->kurir = $data['kurir'];
            $pengiriman->layanan = $data['layanan'];
            $pengiriman->nomor_resi = $data['nomor_resi'];
            $pengiriman->status_pengiriman = 'diproses';

            $pesanan->pengiriman()->save($pengiriman);
        });

        return back()->with(
            'success',
            'Data pengiriman berhasil dibuat.'
        );
    }

    /**
     * Memperbarui status pengiriman.
     */
    public function updateShipment(
        Request $request,
        Pesanan $pesanan
    ): RedirectResponse {

        $data = $request->validate([
            'status_pengiriman' => [
                'required',
                Rule::in([
                    'dikirim',
                    'dalam_perjalanan',
                    'diterima',
                    'gagal',
                ]),
            ],
        ]);

        $pengiriman = $pesanan->pengiriman;

        if (!$pengiriman) {
            return back()->with(
                'error',
                'Data pengiriman belum tersedia.'
            );
        }

        // Aturan perpindahan status pengiriman.
        $transitions = [
            'diproses' => ['dikirim', 'gagal'],
            'dikirim' => [
                'dalam_perjalanan',
                'diterima',
                'gagal',
            ],
            'dalam_perjalanan' => ['diterima', 'gagal'],
            'diterima' => [],
            'gagal' => [],
        ];

        $currentStatus = $pengiriman->status_pengiriman;
        $newStatus = $data['status_pengiriman'];

        if (
            !in_array(
                $newStatus,
                $transitions[$currentStatus] ?? [],
                true
            )
        ) {
            return back()->with(
                'error',
                'Perubahan status pengiriman tidak diperbolehkan.'
            );
        }

        DB::transaction(function () use (
            $pesanan,
            $pengiriman,
            $newStatus
        ) {
            $pengiriman->status_pengiriman = $newStatus;

            if (
                $newStatus === 'dikirim'
                && !$pengiriman->tanggal_pengiriman
            ) {
                $pengiriman->tanggal_pengiriman = now();
            }

            if ($newStatus === 'diterima') {
                if (!$pengiriman->tanggal_pengiriman) {
                    $pengiriman->tanggal_pengiriman = now();
                }

                $pengiriman->tanggal_diterima = now();
            }

            $pengiriman->save();

            // Sinkronisasi status pesanan dengan pengiriman.
            if (in_array($newStatus, [
                'dikirim',
                'dalam_perjalanan',
            ], true)) {
                $pesanan->status_pemesanan = 'dikirim';
                $pesanan->save();
            }

            if ($newStatus === 'diterima') {
                $pesanan->status_pemesanan = 'selesai';
                $pesanan->save();
            }
        });

        return back()->with(
            'success',
            'Status pengiriman berhasil diperbarui.'
        );
    }
}
