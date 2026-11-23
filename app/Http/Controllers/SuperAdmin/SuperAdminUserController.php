<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActionLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminUserController extends Controller
{
    // Menampilkan seluruh pengguna
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in([
                'customer',
                'admin',
                'superadmin',
            ])],
        ]);

        $users = User::query()
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('nomor_telepon', 'like', "%{$search}%");
                });
            })
            ->when($validated['role'] ?? null, function ($query, $role) {
                $query->where('role', $role);
            })
            ->orderByDesc('id_user')
            ->paginate(15);

        return response()->json($users);
    }

    // Form tampilan tambah akun
    public function create()
    {
        return view('super_admin.buat_akun');
    }

    // Membuat akun (Customer, Admin, atau Superadmin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in([
                'customer',
                'admin',
                'superadmin',
            ])],
            'status' => ['nullable', 'string'],
            'must_change_password' => ['nullable'],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
        ]);

        $statusInput = $request->input('status', 'Aktif');
        $isActive = ! in_array(strtolower((string) $statusInput), ['nonaktif', '0', 'inactive'], true);
        $mustChangePassword = $request->boolean('must_change_password');

        $user = User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'] ?? null,
            'username' => $validated['username'],
            'nomor_telepon' => $validated['nomor_telepon'] ?? '-',
            'password_hash' => Hash::make($validated['password']),
            'initial_password' => $validated['password'],
            'role' => $validated['role'],
            'is_active' => $isActive,
            'must_change_password' => $mustChangePassword,
        ]);

        // Catat di Action Log
        ActionLog::record(
            $request->user(),
            'Membuat akun',
            'Akun',
            "{$user->nama_lengkap} · ".ucfirst($user->role),
            'Berhasil'
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Pengguna berhasil dibuat.',
                'data' => $user,
            ], 201);
        }

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Akun '.$user->nama_lengkap.' berhasil dibuat.');
    }

    // Melihat detail pengguna
    public function show(User $user)
    {
        return response()->json([
            'data' => $user,
        ]);
    }

    // Memperbarui data pengguna
    public function update(Request $request, User $user)
    {
        // Tidak boleh mengubah role akun SUPERADMIN melalui fitur ini jika bukan dirinya sendiri.
        if ($user->isSuperAdmin() && $request->has('role') && $request->input('role') !== 'superadmin') {
            abort(403, 'Role SUPERADMIN tidak dapat diubah menjadi role lain.');
        }

        $validated = $request->validate([
            'nama_lengkap' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id_user, 'id_user'),
            ],
            'nomor_telepon' => ['sometimes', 'nullable', 'string', 'max:30'],
            'username' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id_user, 'id_user'),
            ],
            'role' => [
                'sometimes',
                'required',
                Rule::in(['customer', 'admin', 'superadmin']),
            ],
            'is_active' => ['sometimes', 'boolean'],
            'password' => ['sometimes', 'required', 'string', 'min:8'],
        ]);

        if (isset($validated['password'])) {
            $validated['password_hash'] = Hash::make(
                $validated['password']
            );

            unset($validated['password']);
        }

        $user->update($validated);

        ActionLog::record(
            $request->user(),
            'Memperbarui akun',
            'Akun',
            "{$user->nama_lengkap} ({$user->username})",
            'Berhasil'
        );

        return response()->json([
            'message' => 'Pengguna berhasil diperbarui.',
            'data' => $user->fresh(),
        ]);
    }

    // Menghapus akun pengguna
    public function destroy(Request $request, User $user)
    {
        if ($user->id_user === $request->user()->id_user) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Anda tidak dapat menghapus akun Anda sendiri.'], 403);
            }

            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        if ($user->pesanan()->exists()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Akun memiliki riwayat pesanan dan tidak dapat dihapus. Silakan nonaktifkan akun.'], 422);
            }

            return back()->withErrors(['error' => 'Akun memiliki riwayat pesanan dan tidak dapat dihapus. Silakan nonaktifkan akun.']);
        }

        // Hapus relasi yang aman dihapus
        $user->keranjang?->delete();
        $user->alamatPengiriman()->delete();
        $user->ulasan()->delete();

        $userName = $user->nama_lengkap;
        $userHandle = $user->username;
        $userRole = ucfirst($user->role);

        $user->delete();

        ActionLog::record(
            $request->user(),
            'Menghapus akun',
            'Akun',
            "{$userName} ({$userHandle}) · {$userRole}",
            'Berhasil'
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Akun berhasil dihapus.']);
        }

        return redirect()->route('superadmin.dashboard')
            ->with('success', "Akun {$userName} berhasil dihapus.");
    }
}
