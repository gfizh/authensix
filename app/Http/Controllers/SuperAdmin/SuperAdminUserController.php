<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
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

    // Membuat akun CUSTOMER atau ADMIN
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nomor_telepon' => ['required', 'string', 'max:30'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in([
                'customer',
                'admin',
            ])],
        ]);

        $user = User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'nomor_telepon' => $validated['nomor_telepon'],
            'username' => $validated['username'],
            'password_hash' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json([
            'message' => 'Pengguna berhasil dibuat.',
            'data' => $user,
        ], 201);
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
        // Tidak boleh mengubah role akun SUPERADMIN melalui fitur ini.
        if ($user->isSuperAdmin()) {
            abort(403, 'Akun SUPERADMIN tidak dapat diubah melalui fitur ini.');
        }

        $validated = $request->validate([
            'nama_lengkap' => ['sometimes', 'required', 'string', 'max:255'],
            'nomor_telepon' => ['sometimes', 'required', 'string', 'max:30'],
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
                Rule::in(['customer', 'admin']),
            ],
            'password' => ['sometimes', 'required', 'string', 'min:8'],
        ]);

        if (isset($validated['password'])) {
            $validated['password_hash'] = Hash::make(
                $validated['password']
            );

            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Pengguna berhasil diperbarui.',
            'data' => $user->fresh(),
        ]);
    }
}
