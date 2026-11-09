<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ShipmentController extends Controller
{
    /**
     * Menampilkan daftar pengiriman.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => [
                'nullable',
                Rule::in([
                    'diproses',
                    'dikirim',
                    'dalam_perjalanan',
                    'diterima',
                    'gagal',
                ]),
            ],
        ]);

        $search = trim($validated['search'] ?? '');
        $status = $validated['status'] ?? null;

        $pengirimanList = Pengiriman::with([
            'pesanan.user',
            'pesanan.pembayaran',
        ])
            ->when($status, function ($query) use ($status) {
                $query->where('status_pengiriman', $status);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_resi', 'like', "%{$search}%")
                        ->orWhere('kurir', 'like', "%{$search}%")
                        ->orWhere('layanan', 'like', "%{$search}%")
                        ->orWhereHas('pesanan', function ($order) use ($search) {
                            $order->where(
                                'nomor_pesanan',
                                'like',
                                "%{$search}%"
                            )->orWhereHas('user', function ($user) use ($search) {
                                $user->where(
                                    'nama_lengkap',
                                    'like',
                                    "%{$search}%"
                                );
                            });
                        });
                });
            })
            ->orderByDesc('id_pengiriman')
            ->paginate(10)
            ->withQueryString();

        return view('admin.shipments.index', compact(
            'pengirimanList',
            'search',
            'status'
        ));
    }

    /**
     * Menampilkan detail pengiriman.
     */
    public function show(Pengiriman $pengiriman): View
    {
        $pengiriman->load([
            'pesanan.user',
            'pesanan.alamat',
            'pesanan.pembayaran',
        ]);

        return view('admin.shipments.show', compact('pengiriman'));
    }

    /**
     * Membuat data pengiriman baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_pesanan' => [
                'required',
                'integer',
                'exists:pesanan,id_pesanan',
            ],
            'kurir' => ['required', 'string', 'max:255'],
            'layanan' => ['required', 'string', 'max:255'],
            'nomor_resi' => ['required', 'string', 'max:255'],
        ]);

        $pengiriman = DB::transaction(function () use ($validated) {

            // Mengunci pesanan agar tidak diproses bersamaan.
            $pesanan = Pesanan::query()
                ->whereKey($validated['id_pesanan'])
                ->lockForUpdate()
                ->firstOrFail();

            $pesanan->load('pembayaran');

            // Pastikan pembayaran sudah dikonfirmasi.
            if ($pesanan->pembayaran?->status_pembayaran !== 'dibayar') {
                throw ValidationException::withMessages([
                    'id_pesanan' => 'Pesanan belum memiliki pembayaran yang terkonfirmasi.',
                ]);
            }

            // Pengiriman hanya dibuat untuk pesanan yang sedang diproses.
            if ($pesanan->status_pemesanan !== 'diproses') {
                throw ValidationException::withMessages([
                    'id_pesanan' => 'Pesanan harus berstatus diproses sebelum dibuatkan pengiriman.',
                ]);
            }

            // Cegah pengiriman ganda.
            if ($pesanan->pengiriman()->exists()) {
                throw ValidationException::withMessages([
                    'id_pesanan' => 'Pesanan ini sudah memiliki data pengiriman.',
                ]);
            }

            return $pesanan->pengiriman()->create([
                'kurir' => $validated['kurir'],
                'layanan' => $validated['layanan'],
                'nomor_resi' => $validated['nomor_resi'],
                'status_pengiriman' => 'diproses',
                'tanggal_pengiriman' => null,
                'tanggal_diterima' => null,
            ]);
        });

        return redirect()
            ->route('admin.shipments.show', $pengiriman)
            ->with('success', 'Data pengiriman berhasil dibuat.');
    }

    /**
     * Memperbarui informasi pengiriman.
     */
    public function update(
        Request $request,
        Pengiriman $pengiriman
    ): RedirectResponse {
        if (!in_array($pengiriman->status_pengiriman, ['diproses', 'gagal'], true)) {
            throw ValidationException::withMessages([
                'pengiriman' => 'Informasi pengiriman tidak dapat diubah setelah barang dikirim.',
            ]);
        }

        $validated = $request->validate([
            'kurir' => ['required', 'string', 'max:255'],
            'layanan' => ['required', 'string', 'max:255'],
            'nomor_resi' => ['required', 'string', 'max:255'],
        ]);

        $pengiriman->update($validated);

        return redirect()
            ->route('admin.shipments.show', $pengiriman)
            ->with('success', 'Informasi pengiriman berhasil diperbarui.');
    }

    /**
     * Memperbarui status pengiriman dan status pesanan.
     */
    public function updateStatus(
        Request $request,
        Pengiriman $pengiriman
    ): RedirectResponse {
        $validated = $request->validate([
            'status_pengiriman' => [
                'required',
                Rule::in([
                    'diproses',
                    'dikirim',
                    'dalam_perjalanan',
                    'diterima',
                    'gagal',
                ]),
            ],
        ]);

        DB::transaction(function () use ($pengiriman, $validated) {

            // Ambil ulang data dan kunci selama transaksi.
            $pengiriman = Pengiriman::query()
                ->whereKey($pengiriman->id_pengiriman)
                ->lockForUpdate()
                ->firstOrFail();

            $pesanan = Pesanan::query()
                ->whereKey($pengiriman->id_pesanan)
                ->lockForUpdate()
                ->firstOrFail();

            $statusLama = $pengiriman->status_pengiriman;
            $statusBaru = $validated['status_pengiriman'];

            // Aturan perpindahan status.
            $transisi = [
                'diproses' => ['dikirim', 'gagal'],
                'dikirim' => ['dalam_perjalanan', 'diterima', 'gagal'],
                'dalam_perjalanan' => ['diterima', 'gagal'],
                'gagal' => ['diproses'],
                'diterima' => [],
            ];

            if (!in_array($statusBaru, $transisi[$statusLama], true)) {
                throw ValidationException::withMessages([
                    'status_pengiriman' => "Perubahan status dari {$statusLama} ke {$statusBaru} tidak diperbolehkan.",
                ]);
            }

            $dataPengiriman = [
                'status_pengiriman' => $statusBaru,
            ];

            $statusPesanan = null;

            if ($statusBaru === 'dikirim') {
                $dataPengiriman['tanggal_pengiriman'] =
                    $pengiriman->tanggal_pengiriman ?? now();

                $statusPesanan = 'dikirim';
            }

            if ($statusBaru === 'dalam_perjalanan') {
                $dataPengiriman['tanggal_pengiriman'] =
                    $pengiriman->tanggal_pengiriman ?? now();

                $statusPesanan = 'dikirim';
            }

            if ($statusBaru === 'diterima') {
                $dataPengiriman['tanggal_pengiriman'] =
                    $pengiriman->tanggal_pengiriman ?? now();

                $dataPengiriman['tanggal_diterima'] = now();

                $statusPesanan = 'selesai';
            }

            if ($statusBaru === 'diproses') {
                // Pengiriman gagal dicoba kembali.
                $dataPengiriman['tanggal_pengiriman'] = null;
                $dataPengiriman['tanggal_diterima'] = null;

                $statusPesanan = 'diproses';
            }

            $pengiriman->update($dataPengiriman);

            if ($statusPesanan !== null) {
                $pesanan->update([
                    'status_pemesanan' => $statusPesanan,
                ]);
            }
        });

        return redirect()
            ->route('admin.shipments.show', $pengiriman)
            ->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}