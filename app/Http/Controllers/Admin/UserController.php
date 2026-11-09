<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pelanggan.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim($validated['search'] ?? '');

        $users = User::query()
            ->where('role', 'customer')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('nomor_telepon', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Menampilkan detail pelanggan dan riwayat pesanan.
     */
    public function show(User $user): View
    {
        abort_unless($user->role === 'customer', 404);

        $user->load([
            'pesanan' => function ($query) {
                $query->orderByDesc('tanggal_pesanan');
            },
        ]);

        $user->loadCount('pesanan');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Menampilkan data pelanggan untuk diedit.
     */
    public function edit(User $user): View
    {
        abort_unless($user->role === 'customer', 404);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data pelanggan.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'customer', 404);

        $validated = $request->validate([
            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'nomor_telepon' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')
                    ->ignore($user->id_user, 'id_user'),
            ],
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }
}