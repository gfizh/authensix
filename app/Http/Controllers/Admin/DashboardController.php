<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Merek;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Normalisasi Query Parameter
        |--------------------------------------------------------------------------
        */

        $rangeInput = strtolower(
            (string) $request->query('range', '7d')
        );

        $range = match ($rangeInput) {
            '7', '7d' => '7d',
            '30', '30d' => '30d',
            'bulan', 'month' => 'month',
            'tahun', 'year' => 'year',
            default => '7d',
        };

        // Mendukung parameter view dan mode lama.
        $viewInput = strtolower(
            (string) $request->query(
                'view',
                $request->query('mode', 'daily')
            )
        );

        $view = match ($viewInput) {
            'weekly', 'mingguan' => 'weekly',
            default => 'daily',
        };

        // Alias untuk kompatibilitas kode lama.
        $mode = $view === 'weekly' ? 'mingguan' : 'harian';

        /*
        |--------------------------------------------------------------------------
        | 2. Menentukan Periode dan Periode Sebelumnya
        |--------------------------------------------------------------------------
        */

        $sekarang = now();

        switch ($range) {
            case '7d':
            case '30d':

                $jumlahHari = $range === '7d' ? 7 : 30;

                $periodeMulai = $sekarang->copy()
                    ->startOfDay()
                    ->subDays($jumlahHari - 1);

                $periodeSelesai = $sekarang->copy()
                    ->endOfDay();

                $periodeSebelumnyaMulai = $periodeMulai->copy()
                    ->subDays($jumlahHari);

                $periodeSebelumnyaSelesai = $periodeMulai->copy()
                    ->subSecond();

                break;

            case 'month':

                $periodeMulai = $sekarang->copy()
                    ->startOfMonth();

                $periodeSelesai = $sekarang->copy()
                    ->endOfDay();

                $periodeSebelumnyaMulai = $periodeMulai->copy()
                    ->subMonthNoOverflow();

                $periodeSebelumnyaSelesai = $periodeSelesai->copy()
                    ->subMonthNoOverflow();

                break;

            case 'year':

                $periodeMulai = $sekarang->copy()
                    ->startOfYear();

                $periodeSelesai = $sekarang->copy()
                    ->endOfDay();

                $periodeSebelumnyaMulai = $periodeMulai->copy()
                    ->subYear();

                $periodeSebelumnyaSelesai = $periodeSelesai->copy()
                    ->subYear();

                break;
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Fungsi Persentase Pertumbuhan
        |--------------------------------------------------------------------------
        */

        $hitungPersentase = function (
            float|int $sekarang,
            float|int $sebelumnya
        ): float {
            if ($sebelumnya <= 0) {
                return $sekarang > 0 ? 100.0 : 0.0;
            }

            return round(
                (($sekarang - $sebelumnya) / $sebelumnya) * 100,
                2
            );
        };

        /*
        |--------------------------------------------------------------------------
        | 4. Statistik Berdasarkan Periode
        |--------------------------------------------------------------------------
        */

        // Produk yang ditambahkan pada periode terpilih.
        $produkPeriodeIni = Produk::whereBetween('created_at', [
            $periodeMulai,
            $periodeSelesai,
        ])->count();

        $produkPeriodeSebelumnya = Produk::whereBetween('created_at', [
            $periodeSebelumnyaMulai,
            $periodeSebelumnyaSelesai,
        ])->count();

        // Pesanan berdasarkan tanggal pesanan.
        $pesananPeriodeIni = Pesanan::whereBetween('tanggal_pesanan', [
            $periodeMulai,
            $periodeSelesai,
        ])->count();

        $pesananPeriodeSebelumnya = Pesanan::whereBetween('tanggal_pesanan', [
            $periodeSebelumnyaMulai,
            $periodeSebelumnyaSelesai,
        ])->count();

        // Pelanggan baru berdasarkan tanggal registrasi.
        $pelangganPeriodeIni = User::where('role', 'customer')
            ->whereBetween('created_at', [
                $periodeMulai,
                $periodeSelesai,
            ])
            ->count();

        $pelangganPeriodeSebelumnya = User::where('role', 'customer')
            ->whereBetween('created_at', [
                $periodeSebelumnyaMulai,
                $periodeSebelumnyaSelesai,
            ])
            ->count();

        // Pendapatan hanya dari pembayaran berhasil.
        $pendapatanPeriodeIni = Pembayaran::where(
            'status_pembayaran',
            'dibayar'
        )
            ->whereBetween('tanggal_pembayaran', [
                $periodeMulai,
                $periodeSelesai,
            ])
            ->sum('jumlah_bayar');

        $pendapatanPeriodeSebelumnya = Pembayaran::where(
            'status_pembayaran',
            'dibayar'
        )
            ->whereBetween('tanggal_pembayaran', [
                $periodeSebelumnyaMulai,
                $periodeSebelumnyaSelesai,
            ])
            ->sum('jumlah_bayar');

        // Status pembayaran berdasarkan tanggal pembayaran atau tanggal pesanan terkait.
        $pembayaranPeriodeCounts = Pembayaran::query()
            ->where(function ($query) use ($periodeMulai, $periodeSelesai): void {
                $query->whereBetween('tanggal_pembayaran', [
                    $periodeMulai,
                    $periodeSelesai,
                ])->orWhere(function ($subQuery) use ($periodeMulai, $periodeSelesai): void {
                    $subQuery->whereNull('tanggal_pembayaran')
                        ->whereHas('pesanan', function ($orderQuery) use ($periodeMulai, $periodeSelesai): void {
                            $orderQuery->whereBetween('tanggal_pesanan', [
                                $periodeMulai,
                                $periodeSelesai,
                            ]);
                        });
                });
            })
            ->select('status_pembayaran', DB::raw('COUNT(*) as total'))
            ->groupBy('status_pembayaran')
            ->pluck('total', 'status_pembayaran');

        /*
        |--------------------------------------------------------------------------
        | 5. Statistik Utama Dashboard
        |--------------------------------------------------------------------------
        */

        $stats = [

            // Nilai empat kartu utama mengikuti periode terpilih.
            'total_produk' => $produkPeriodeIni,
            'total_pesanan' => $pesananPeriodeIni,
            'total_pelanggan' => $pelangganPeriodeIni,
            'total_pendapatan' => $pendapatanPeriodeIni,

            // Total keseluruhan untuk referensi.
            'total_produk_keseluruhan' => Produk::count(),

            'total_pesanan_keseluruhan' => Pesanan::count(),

            'total_pelanggan_keseluruhan' => User::where(
                'role',
                'customer'
            )->count(),

            'total_pendapatan_keseluruhan' => Pembayaran::where(
                'status_pembayaran',
                'dibayar'
            )->sum('jumlah_bayar'),

            // Pertumbuhan.
            'persentase_produk' => $hitungPersentase(
                $produkPeriodeIni,
                $produkPeriodeSebelumnya
            ),

            'persentase_pesanan' => $hitungPersentase(
                $pesananPeriodeIni,
                $pesananPeriodeSebelumnya
            ),

            'persentase_pelanggan' => $hitungPersentase(
                $pelangganPeriodeIni,
                $pelangganPeriodeSebelumnya
            ),

            'persentase_pendapatan' => $hitungPersentase(
                (float) $pendapatanPeriodeIni,
                (float) $pendapatanPeriodeSebelumnya
            ),

            // Produk.
            'produk_aktif' => Produk::where('is_active', true)->count(),

            'produk_nonaktif' => Produk::where('is_active', false)->count(),

            'stok_habis' => Produk::where('is_active', true)
                ->where('stok', '<=', 0)
                ->count(),

            'stok_menipis' => Produk::where('is_active', true)
                ->whereBetween('stok', [1, 5])
                ->count(),

            // Kategori dan merek.
            'total_kategori' => Kategori::count(),

            'total_merek' => Merek::count(),

            // Status pesanan.
            'pesanan_pending' => Pesanan::whereBetween('tanggal_pesanan', [
                $periodeMulai,
                $periodeSelesai,
            ])->where('status_pemesanan', 'pending')->count(),

            'pesanan_diproses' => Pesanan::whereBetween('tanggal_pesanan', [
                $periodeMulai,
                $periodeSelesai,
            ])->where('status_pemesanan', 'diproses')->count(),

            'pesanan_dikirim' => Pesanan::whereBetween('tanggal_pesanan', [
                $periodeMulai,
                $periodeSelesai,
            ])->where('status_pemesanan', 'dikirim')->count(),

            'pesanan_selesai' => Pesanan::whereBetween('tanggal_pesanan', [
                $periodeMulai,
                $periodeSelesai,
            ])->where('status_pemesanan', 'selesai')->count(),

            'pesanan_dibatalkan' => Pesanan::whereBetween('tanggal_pesanan', [
                $periodeMulai,
                $periodeSelesai,
            ])->where('status_pemesanan', 'dibatalkan')->count(),

            // Status pembayaran berdasarkan tanggal pembayaran / tanggal pesanan terkait.
            'total_pembayaran' => (int) $pembayaranPeriodeCounts->sum(),

            'pembayaran_pending' => (int) ($pembayaranPeriodeCounts['pending'] ?? 0),

            'pembayaran_dibayar' => (int) ($pembayaranPeriodeCounts['dibayar'] ?? 0),

            'pembayaran_gagal' => (int) ($pembayaranPeriodeCounts['gagal'] ?? 0),

            'pembayaran_expired' => (int) ($pembayaranPeriodeCounts['expired'] ?? 0),

            // Pendapatan hari ini.
            'pendapatan_hari_ini' => Pembayaran::where(
                'status_pembayaran',
                'dibayar'
            )
                ->whereDate('tanggal_pembayaran', today())
                ->sum('jumlah_bayar'),

            // Pendapatan bulan ini.
            'pendapatan_bulan_ini' => Pembayaran::where(
                'status_pembayaran',
                'dibayar'
            )
                ->whereMonth('tanggal_pembayaran', now()->month)
                ->whereYear('tanggal_pembayaran', now()->year)
                ->sum('jumlah_bayar'),
        ];

        /*
        |--------------------------------------------------------------------------
        | 6. Produk Terbaru
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
        | 7. Produk Stok Menipis
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
        | 8. Pesanan Terbaru
        |--------------------------------------------------------------------------
        */

        $pesananTerbaru = Pesanan::with('user')
            ->orderByDesc('tanggal_pesanan')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 9. Pembayaran Terbaru
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
        | 10. Produk Terlaris
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
        | 11. Membuat Dataset Grafik Harian
        |--------------------------------------------------------------------------
        */

        $awalPeriode = $periodeMulai->copy()->startOfDay();
        $akhirPeriode = $periodeSelesai->copy()->startOfDay();

        $grafikPenjualanHarian = [];

        $cursor = $awalPeriode->copy();

        while ($cursor->lte($akhirPeriode)) {
            $key = $cursor->format('Y-m-d');

            $grafikPenjualanHarian[$key] = [
                'label' => $cursor->locale('id')->translatedFormat('d M'),
                'tanggal' => $key,
                'hari' => $cursor->dayOfWeekIso,
                'total' => 0.0,
                'transaksi' => 0,
            ];

            $cursor->addDay();
        }

        /*
        |--------------------------------------------------------------------------
        | 12. Membuat Dataset Grafik Mingguan
        |--------------------------------------------------------------------------
        */

        $jumlahHari = (int) $awalPeriode->diffInDays($akhirPeriode) + 1;

        $jumlahMinggu = (int) ceil($jumlahHari / 7);

        $grafikPenjualanMingguan = [];

        for ($i = 1; $i <= $jumlahMinggu; $i++) {
            $tanggalMulaiMinggu = $awalPeriode->copy()
                ->addDays(($i - 1) * 7);

            $tanggalSelesaiMinggu = $tanggalMulaiMinggu->copy()
                ->addDays(6)
                ->endOfDay();

            if ($tanggalSelesaiMinggu->greaterThan($periodeSelesai)) {
                $tanggalSelesaiMinggu = $periodeSelesai->copy();
            }

            $key = 'week_'.$i;

            $grafikPenjualanMingguan[$key] = [
                'label' => 'Minggu '.$i,
                'tanggal_mulai' => $tanggalMulaiMinggu->format('Y-m-d'),
                'tanggal_selesai' => $tanggalSelesaiMinggu->format('Y-m-d'),
                'total' => 0.0,
                'transaksi' => 0,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 13. Mengambil Pembayaran Berhasil pada Periode Terpilih
        |--------------------------------------------------------------------------
        */

        $pembayaranSelesai = Pembayaran::where(
            'status_pembayaran',
            'dibayar'
        )
            ->whereNotNull('tanggal_pembayaran')
            ->whereBetween('tanggal_pembayaran', [
                $periodeMulai,
                $periodeSelesai,
            ])
            ->get([
                'tanggal_pembayaran',
                'jumlah_bayar',
            ]);

        foreach ($pembayaranSelesai as $bayar) {
            $tanggal = Carbon::parse($bayar->tanggal_pembayaran);

            $jumlahBayar = (float) $bayar->jumlah_bayar;

            // Masukkan ke grafik harian.
            $tanggalKey = $tanggal->format('Y-m-d');

            if (isset($grafikPenjualanHarian[$tanggalKey])) {
                $grafikPenjualanHarian[$tanggalKey]['total'] += $jumlahBayar;

                $grafikPenjualanHarian[$tanggalKey]['transaksi']++;
            }

            // Masukkan ke grafik mingguan.
            $selisihHari = (int) $awalPeriode->diffInDays(
                $tanggal->copy()->startOfDay()
            );

            $nomorMinggu = intdiv($selisihHari, 7) + 1;

            $mingguKey = 'week_'.$nomorMinggu;

            if (isset($grafikPenjualanMingguan[$mingguKey])) {
                $grafikPenjualanMingguan[$mingguKey]['total'] += $jumlahBayar;

                $grafikPenjualanMingguan[$mingguKey]['transaksi']++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 14. Dataset Grafik Aktif
        |--------------------------------------------------------------------------
        */

        $grafikPenjualan = $view === 'weekly'
            ? $grafikPenjualanMingguan
            : $grafikPenjualanHarian;

        $grafikPenjualanAktif = $grafikPenjualan;

        /*
        |--------------------------------------------------------------------------
        | 15. Mengirim Data ke Dashboard
        |--------------------------------------------------------------------------
        */

        $viewName = view()->exists('admin.dashboard-admin')
            ? 'admin.dashboard-admin'
            : 'admin.dashboard';

        return view($viewName, compact(
            'stats',
            'produkTerbaru',
            'produkStokMenipis',
            'pesananTerbaru',
            'pembayaranTerbaru',
            'produkTerlaris',
            'grafikPenjualan',
            'grafikPenjualanHarian',
            'grafikPenjualanMingguan',
            'grafikPenjualanAktif',
            'range',
            'view',
            'mode',
            'periodeMulai',
            'periodeSelesai',
            'periodeSebelumnyaMulai',
            'periodeSebelumnyaSelesai'
        ));
    }
}
