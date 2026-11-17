<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActionLog;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuperAdminActionLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search') ?? $request->input('q');
        $dateFilter = $request->input('date', 'all');
        $userFilter = $request->input('user');
        $moduleFilter = $request->input('modul');
        $statusFilter = $request->input('status');

        $query = ActionLog::query()
            ->when($search, function ($q, $search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('user_name', 'like', "%{$search}%")
                        ->orWhere('aktivitas', 'like', "%{$search}%")
                        ->orWhere('detail', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%");
                });
            })
            ->when($userFilter, function ($q, $user) {
                $q->where(function ($sq) use ($user) {
                    $sq->where('id_user', $user)
                        ->orWhere('user_name', 'like', "%{$user}%");
                });
            })
            ->when($moduleFilter, function ($q, $modul) {
                $q->where('modul', $modul);
            })
            ->when($statusFilter, function ($q, $status) {
                if ($status === 'success' || $status === 'Berhasil') {
                    $q->where('status', 'Berhasil');
                } elseif ($status === 'failed' || $status === 'Gagal') {
                    $q->where('status', 'Gagal');
                }
            })
            ->when($dateFilter === '7', function ($q) {
                $q->where('created_at', '>=', now()->subDays(7));
            })
            ->when($dateFilter === '30', function ($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            })
            ->orderByDesc('id_action_log');

        if ($request->wantsJson()) {
            return response()->json($query->paginate(15));
        }

        $logs = $query->paginate(15)->withQueryString();
        $usersList = User::select('id_user', 'nama_lengkap', 'username', 'role')->get();

        return view('super_admin.action_log', [
            'logs' => $logs,
            'usersList' => $usersList,
            'filters' => [
                'search' => $search,
                'date' => $dateFilter,
                'user' => $userFilter,
                'modul' => $moduleFilter,
                'status' => $statusFilter,
            ],
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $search = $request->input('search') ?? $request->input('q');
        $moduleFilter = $request->input('modul');
        $statusFilter = $request->input('status');

        $query = ActionLog::query()
            ->when($search, function ($q, $search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('user_name', 'like', "%{$search}%")
                        ->orWhere('aktivitas', 'like', "%{$search}%")
                        ->orWhere('detail', 'like', "%{$search}%");
                });
            })
            ->when($moduleFilter, function ($q, $modul) {
                $q->where('modul', $modul);
            })
            ->when($statusFilter, function ($q, $status) {
                if ($status === 'success' || $status === 'Berhasil') {
                    $q->where('status', 'Berhasil');
                } elseif ($status === 'failed' || $status === 'Gagal') {
                    $q->where('status', 'Gagal');
                }
            })
            ->orderByDesc('id_action_log');

        $fileName = 'action_logs_'.date('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Waktu (WIB)', 'Pengguna', 'Role', 'Aktivitas', 'Detail', 'Modul', 'Alamat IP', 'Status']);

            $query->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->id_action_log,
                        $row->created_at->format('Y-m-d H:i:s'),
                        $row->user_name,
                        $row->user_role,
                        $row->aktivitas,
                        $row->detail,
                        $row->modul,
                        $row->ip_address,
                        $row->status,
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
