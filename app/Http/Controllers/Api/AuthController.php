<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController
{
    use ApiResponse;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'Validasi gagal',
                $validator->errors(),
                422
            );
        }

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check(
            $request->password,
            $user->password_hash
        )) {
            return $this->errorResponse(
                'Username atau password salah',
                null,
                401
            );
        }

        $token = null;

        // Aktif jika User menggunakan Laravel Sanctum.
        if (method_exists($user, 'createToken')) {
            $token = $user
                ->createToken('api-token')
                ->plainTextToken;
        }

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
            'token_type' => $token ? 'Bearer' : null,
        ], 'Login berhasil');
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()
                ->currentAccessToken()?->delete();
        }

        return $this->successResponse(
            null,
            'Logout berhasil'
        );
    }
}