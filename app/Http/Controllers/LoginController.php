<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        $quickLoginUsers = User::query()
            ->orderBy('id_user')
            ->get();

        return view('login', compact('quickLoginUsers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            ActionLog::record(null, 'Percobaan masuk ditolak', 'Autentikasi', "Username: {$request->input('username')}", 'Gagal');

            return back()
                ->withErrors([
                    'username' => 'Username atau password salah.',
                ])
                ->onlyInput('username');
        }

        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        $user = $request->user();
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            ActionLog::record($user, 'Masuk ke sistem', 'Autentikasi', 'Sesi baru dimulai', 'Berhasil');
        }

        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard-admin');
        }

        return redirect()->route('dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user && ($user->isSuperAdmin() || $user->isAdmin())) {
            ActionLog::record($user, 'Keluar dari sistem', 'Autentikasi', 'Sesi diakhiri', 'Berhasil');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
