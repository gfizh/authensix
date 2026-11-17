<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActionLog;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $usersData = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'customer' => User::where('role', 'customer')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'superadmin' => User::where('role', 'superadmin')->count(),
            'new_this_week' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $activitiesData = [
            'today' => ActionLog::whereDate('created_at', today())->count(),
            'today_success' => ActionLog::whereDate('created_at', today())->where('status', 'Berhasil')->count(),
            'today_failed' => ActionLog::whereDate('created_at', today())->where('status', 'Gagal')->count(),
            'total' => ActionLog::count(),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Dashboard SUPERADMIN.',
                'users' => $usersData,
                'activities' => $activitiesData,
                'transactions' => [
                    'total' => Pesanan::count(),
                    'pending' => Pesanan::where('status_pemesanan', 'pending')->count(),
                    'diproses' => Pesanan::where('status_pemesanan', 'diproses')->count(),
                    'dikirim' => Pesanan::where('status_pemesanan', 'dikirim')->count(),
                    'selesai' => Pesanan::where('status_pemesanan', 'selesai')->count(),
                    'dibatalkan' => Pesanan::where('status_pemesanan', 'dibatalkan')->count(),
                ],
                'payments' => [
                    'total' => Pembayaran::count(),
                    'dibayar' => Pembayaran::where('status_pembayaran', 'dibayar')->count(),
                    'pending' => Pembayaran::where('status_pembayaran', 'pending')->count(),
                    'gagal' => Pembayaran::where('status_pembayaran', 'gagal')->count(),
                    'expired' => Pembayaran::where('status_pembayaran', 'expired')->count(),
                ],
            ]);
        }

        // Hitung aktivitas 7 hari terakhir untuk grafik mingguan
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $weeklyActivities = [];
        $maxCount = 0;

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $cnt = ActionLog::whereDate('created_at', $date->toDateString())->count();
            if ($cnt > $maxCount) {
                $maxCount = $cnt;
            }
            $weeklyActivities[] = [
                'day' => $dayNames[$date->dayOfWeek],
                'date' => $date->format('Y-m-d'),
                'count' => $cnt,
                'is_today' => ($i === 0),
            ];
        }

        foreach ($weeklyActivities as &$day) {
            $day['height'] = $maxCount > 0 ? max(15, (int) round(($day['count'] / $maxCount) * 100)) : 10;
        }
        unset($day);

        $recentActivities = ActionLog::latest('id_action_log')->take(8)->get();

        return view('super_admin.dashboard', [
            'users' => $usersData,
            'activities' => $activitiesData,
            'weeklyActivities' => $weeklyActivities,
            'recentActivities' => $recentActivities,
        ]);
    }
}
