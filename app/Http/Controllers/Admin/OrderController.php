<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman manajemen pesanan dan produk untuk admin.
     */
    public function index(Request $request, string $defaultTab = 'pesanan'): View
    {
        $viewData = $this->buildManagementViewData($request, $defaultTab);

        return view('admin.manajemen_produk', $viewData);
    }

    /**
     * Menyusun data pesanan dan produk terintegrasi untuk tampilan manajemen admin.
     *
     * @return array<string, mixed>
     */
    public function buildManagementViewData(Request $request, string $defaultTab = 'pesanan'): array
    {
        $filters = $request->validate([
            'tab' => ['nullable', Rule::in(['pesanan', 'produk'])],
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
            'product_status' => ['nullable', Rule::in(['all', 'active', 'inactive'])],
        ]);

        $activeTab = $filters['tab'] ?? $defaultTab;

        $orderCounts = Pesanan::query()
            ->select('status_pemesanan', DB::raw('COUNT(*) as total'))
            ->groupBy('status_pemesanan')
            ->pluck('total', 'status_pemesanan');

        $orderStats = [
            'total' => (int) $orderCounts->sum(),
            'pending' => (int) ($orderCounts['pending'] ?? 0),
            'diproses' => (int) ($orderCounts['diproses'] ?? 0),
            'dikirim' => (int) ($orderCounts['dikirim'] ?? 0),
            'selesai' => (int) ($orderCounts['selesai'] ?? 0),
            'dibatalkan' => (int) ($orderCounts['dibatalkan'] ?? 0),
        ];

        $productStats = [
            'total' => Produk::count(),
            'aktif' => Produk::where('is_active', true)->count(),
            'nonaktif' => Produk::where('is_active', false)->count(),
            'stok_menipis' => Produk::where('is_active', true)->where('stok', '<=', 5)->count(),
        ];

        $orders = Pesanan::query()
            ->with([
                'user',
                'alamat',
                'items.produk',
                'pembayaran',
                'pengiriman',
            ])
            ->when(
                $activeTab === 'pesanan' && filled($filters['search'] ?? null),
                function ($query) use ($filters): void {
                    $search = '%'.$filters['search'].'%';

                    $query->where(function ($subQuery) use ($search): void {
                        $subQuery
                            ->where('nomor_pesanan', 'like', $search)
                            ->orWhereHas('user', function ($userQuery) use ($search): void {
                                $userQuery->where('nama_lengkap', 'like', $search)
                                    ->orWhere('username', 'like', $search);
                            })
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
            ->paginate(10, ['*'], 'orders_page')
            ->withQueryString();

        $productStatusFilter = $filters['product_status'] ?? 'all';

        $products = Produk::query()
            ->with(['kategori', 'merek'])
            ->when(
                $activeTab === 'produk' && filled($filters['search'] ?? null),
                function ($query) use ($filters): void {
                    $search = '%'.$filters['search'].'%';

                    $query->where(function ($subQuery) use ($search): void {
                        $subQuery->where('nama_produk', 'like', $search)
                            ->orWhere('sku', 'like', $search);
                    });
                }
            )
            ->when(
                $productStatusFilter === 'active',
                fn ($query) => $query->where('is_active', true)
            )
            ->when(
                $productStatusFilter === 'inactive',
                fn ($query) => $query->where('is_active', false)
            )
            ->orderByDesc('id_produk')
            ->paginate(10, ['*'], 'products_page')
            ->withQueryString();

        return compact(
            'activeTab',
            'orders',
            'orderStats',
            'products',
            'productStats',
            'filters'
        );
    }

    /**
     * Menampilkan detail pesanan.
     */
    public function show(Pesanan $pesanan): View
    {
        $pesanan->load([
            'user',
            'alamat',
            'items.produk',
            'pembayaran',
            'invoice',
            'pengiriman',
        ]);

        return view('admin.orders.show', compact('pesanan'));
    }

    /**
     * Memperbarui status pesanan (termasuk ACC Dikirim, Selesai, atau Dibatalkan).
     */
    public function updateStatus(
        Request $request,
        Pesanan $pesanan
    ): RedirectResponse {
        $data = $request->validate([
            'status_pemesanan' => [
                'required',
                Rule::in([
                    'pending',
                    'diproses',
                    'dikirim',
                    'selesai',
                    'dibatalkan',
                ]),
            ],
            'kurir' => ['nullable', 'string', 'max:100'],
            'layanan' => ['nullable', 'string', 'max:100'],
            'nomor_resi' => ['nullable', 'string', 'max:100'],
        ]);

        $newStatus = $data['status_pemesanan'];

        if ($newStatus === 'dibatalkan') {
            if (! $pesanan->cancelAndRestoreStock()) {
                return back()->with(
                    'error',
                    'Pesanan yang sudah dikirim, selesai, atau dibatalkan tidak dapat dibatalkan.'
                );
            }

            return back()->with(
                'success',
                "Pesanan {$pesanan->nomor_pesanan} berhasil dibatalkan dan stok produk telah dikembalikan."
            );
        }

        $errorMessage = DB::transaction(function () use ($pesanan, $data, $newStatus): ?string {
            $lockedOrder = Pesanan::query()
                ->with('pengiriman')
                ->lockForUpdate()
                ->findOrFail($pesanan->id_pesanan);

            if ($lockedOrder->status_pemesanan === 'dibatalkan') {
                return 'Pesanan sudah dibatalkan oleh kostumer/admin dan tidak dapat diproses lagi.';
            }

            if ($lockedOrder->status_pemesanan === 'selesai' && $newStatus !== 'selesai') {
                return 'Pesanan yang sudah selesai tidak dapat diubah kembali.';
            }

            if ($newStatus === 'dikirim') {
                $lockedOrder->status_pemesanan = 'dikirim';
                $lockedOrder->save();

                $pengiriman = $lockedOrder->pengiriman;
                $kurir = trim((string) ($data['kurir'] ?? '')) ?: ($pengiriman?->kurir ?: 'Kurir Toko');
                $layanan = trim((string) ($data['layanan'] ?? '')) ?: ($pengiriman?->layanan ?: 'REG');
                $nomorResi = trim((string) ($data['nomor_resi'] ?? ''))
                    ?: ($pengiriman?->nomor_resi ?: 'RESI-'.$lockedOrder->id_pesanan.'-'.now()->format('His'));

                if ($pengiriman) {
                    $pengiriman->update([
                        'kurir' => $kurir,
                        'layanan' => $layanan,
                        'nomor_resi' => $nomorResi,
                        'status_pengiriman' => 'dikirim',
                        'tanggal_pengiriman' => $pengiriman->tanggal_pengiriman ?? now(),
                    ]);
                } else {
                    $lockedOrder->pengiriman()->create([
                        'kurir' => $kurir,
                        'layanan' => $layanan,
                        'nomor_resi' => $nomorResi,
                        'status_pengiriman' => 'dikirim',
                        'tanggal_pengiriman' => now(),
                        'tanggal_diterima' => null,
                    ]);
                }

                return null;
            }

            if ($newStatus === 'selesai') {
                $lockedOrder->status_pemesanan = 'selesai';
                $lockedOrder->save();

                if ($lockedOrder->pengiriman) {
                    $lockedOrder->pengiriman->update([
                        'status_pengiriman' => 'diterima',
                        'tanggal_pengiriman' => $lockedOrder->pengiriman->tanggal_pengiriman ?? now(),
                        'tanggal_diterima' => now(),
                    ]);
                }

                return null;
            }

            $lockedOrder->status_pemesanan = $newStatus;
            $lockedOrder->save();

            return null;
        });

        if ($errorMessage !== null) {
            return back()->with('error', $errorMessage);
        }

        $label = $newStatus === 'dikirim' ? 'Dikirim' : ucfirst($newStatus);

        return back()->with(
            'success',
            "Pesanan {$pesanan->nomor_pesanan} berhasil di-ACC menjadi {$label}."
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
            'kurir' => ['required', 'string', 'max:255'],
            'layanan' => ['required', 'string', 'max:255'],
            'nomor_resi' => ['required', 'string', 'max:255'],
        ]);

        if (! in_array($pesanan->status_pemesanan, ['pending', 'diproses'], true)) {
            return back()->with(
                'error',
                'Pesanan tidak dalam status yang dapat dibuatkan pengiriman.'
            );
        }

        if ($pesanan->pengiriman()->exists()) {
            return back()->with(
                'error',
                'Data pengiriman untuk pesanan ini sudah tersedia.'
            );
        }

        DB::transaction(function () use ($pesanan, $data): void {
            $pengiriman = new Pengiriman;

            $pengiriman->kurir = $data['kurir'];
            $pengiriman->layanan = $data['layanan'];
            $pengiriman->nomor_resi = $data['nomor_resi'];
            $pengiriman->status_pengiriman = 'dikirim';
            $pengiriman->tanggal_pengiriman = now();

            $pesanan->pengiriman()->save($pengiriman);
            $pesanan->update(['status_pemesanan' => 'dikirim']);
        });

        return back()->with(
            'success',
            'Data pengiriman berhasil dibuat dan pesanan berstatus Dikirim.'
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

        if (! $pengiriman) {
            return back()->with(
                'error',
                'Data pengiriman belum tersedia.'
            );
        }

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
            ! in_array(
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
        ): void {
            $pengiriman->status_pengiriman = $newStatus;

            if (
                $newStatus === 'dikirim'
                && ! $pengiriman->tanggal_pengiriman
            ) {
                $pengiriman->tanggal_pengiriman = now();
            }

            if ($newStatus === 'diterima') {
                if (! $pengiriman->tanggal_pengiriman) {
                    $pengiriman->tanggal_pengiriman = now();
                }

                $pengiriman->tanggal_diterima = now();
            }

            $pengiriman->save();

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
