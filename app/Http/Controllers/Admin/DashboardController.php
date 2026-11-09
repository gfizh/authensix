<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Merek;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Statistik Utama
        |--------------------------------------------------------------------------
        */

        $stats = [

            // Produk
            'total_produk' => Produk::count(),

            'produk_aktif' => Produk::where('is_active', true)->count(),

            'produk_nonaktif' => Produk::where('is_active', false)->count(),

            'stok_habis' => Produk::where('is_active', true)
                ->where('stok', '<=', 0)
                ->count(),

            'stok_menipis' => Produk::where('is_active', true)
                ->whereBetween('stok', [1, 5])
                ->count(),

            // Kategori dan Merek
            'total_kategori' => Kategori::count(),

            'total_merek' => Merek::count(),

            // Pesanan
            'total_pesanan' => Pesanan::count(),

            'pesanan_pending' => Pesanan::where(
                'status_pemesanan', 'pending'
            )->count(),

            'pesanan_diproses' => Pesanan::where(
                'status_pemesanan', 'diproses'
            )->count(),

            'pesanan_dikirim' => Pesanan::where(
                'status_pemesanan', 'dikirim'
            )->count(),

            'pesanan_selesai' => Pesanan::where(
                'status_pemesanan', 'selesai'
            )->count(),

            'pesanan_dibatalkan' => Pesanan::where(
                'status_pemesanan', 'dibatalkan'
            )->count(),

            // Pelanggan
            'total_pelanggan' => User::where(
                'role', 'customer'
            )->count(),

            // Pembayaran
            'total_pembayaran' => Pembayaran::count(),

            'pembayaran_pending' => Pembayaran::where(
                'status_pembayaran', 'pending'
            )->count(),

            'pembayaran_dibayar' => Pembayaran::where(
                'status_pembayaran', 'dibayar'
            )->count(),

            'pembayaran_gagal' => Pembayaran::where(
                'status_pembayaran', 'gagal'
            )->count(),

            'pembayaran_expired' => Pembayaran::where(
                'status_pembayaran', 'expired'
            )->count(),

            // Pendapatan dari pembayaran yang sudah dikonfirmasi
            'total_pendapatan' => Pembayaran::where(
                'status_pembayaran', 'dibayar'
            )->sum('jumlah_bayar'),

            // Pendapatan hari ini
            'pendapatan_hari_ini' => Pembayaran::where(
                'status_pembayaran', 'dibayar'
            )
                ->whereDate('tanggal_pembayaran', today())
                ->sum('jumlah_bayar'),

            // Pendapatan bulan ini
            'pendapatan_bulan_ini' => Pembayaran::where(
                'status_pembayaran', 'dibayar'
            )
                ->whereMonth('tanggal_pembayaran', now()->month)
                ->whereYear('tanggal_pembayaran', now()->year)
                ->sum('jumlah_bayar'),
        ];

        /*
        |--------------------------------------------------------------------------
        | 2. Produk Terbaru
        |--------------------------------------------------------------------------
        */

        $produkTerbaru = Produk::with([
            'kategori',
            'merek',
        ])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 3. Produk dengan Stok Menipis
        |--------------------------------------------------------------------------
        */

        $produkStokMenipis = Produk::with([
            'kategori',
            'merek',
        ])
            ->where('is_active', true)
            ->whereBetween('stok', [1, 5])
            ->orderBy('stok')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 4. Pesanan Terbaru
        |--------------------------------------------------------------------------
        */

        $pesananTerbaru = Pesanan::with('user')
            ->orderByDesc('tanggal_pesanan')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 5. Pembayaran Terbaru
        |--------------------------------------------------------------------------
        */

        $pembayaranTerbaru = Pembayaran::with([
            'pesanan.user',
        ])
            ->orderByDesc('id_pembayaran')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 6. Produk Terlaris
        |--------------------------------------------------------------------------
        */

        $produkTerlaris = Produk::with([
            'kategori',
            'merek',
        ])
            ->where('is_active', true)
            ->orderByDesc('jumlah_terjual')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 7. Data Grafik Penjualan Harian (Senin - Minggu)
        |--------------------------------------------------------------------------
        */

        $namaHari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        $grafikPenjualan = collect($namaHari)->mapWithKeys(fn ($nama) => [
            $nama => [
                'hari' => $nama,
                'total' => 0.0,
                'transaksi' => 0,
            ],
        ])->toArray();

        $pembayaranSelesai = Pembayaran::where('status_pembayaran', 'dibayar')->get();
        foreach ($pembayaranSelesai as $bayar) {
            $tgl = $bayar->tanggal_pembayaran;
            if ($tgl) {
                $hariKe = $tgl->dayOfWeekIso;
                if (isset($namaHari[$hariKe])) {
                    $label = $namaHari[$hariKe];
                    $grafikPenjualan[$label]['total'] += (float) $bayar->jumlah_bayar;
                    $grafikPenjualan[$label]['transaksi'] += 1;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 8. Kirim Data ke Dashboard
        |--------------------------------------------------------------------------
        */

        $viewName = view()->exists('admin.dashboard-admin') ? 'admin.dashboard-admin' : 'admin.dashboard';

        return view($viewName, compact(
            'stats',
            'produkTerbaru',
            'produkStokMenipis',
            'pesananTerbaru',
            'pembayaranTerbaru',
            'produkTerlaris',
            'grafikPenjualan'
        ));
    }
}
