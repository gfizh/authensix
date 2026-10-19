<?php

namespace App\Http\Controllers\Api;

use App\Models\AlamatPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController
{
    use ApiResponse;

    public function index(Request $request)
    {
        $addresses = $request->user()
            ->alamatPengiriman()
            ->get();

        return $this->successResponse(
            $addresses,
            'Data alamat berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label_alamat' => [
                'required',
                'string',
                'max:100'
            ],

            'nama_penerima' => [
                'required',
                'string',
                'max:150'
            ],

            'nomor_telepon' => [
                'required',
                'string',
                'max:30'
            ],

            'alamat_lengkap' => [
                'required',
                'string'
            ],

            'kota' => [
                'required',
                'string',
                'max:100'
            ],

            'provinsi' => [
                'required',
                'string',
                'max:100'
            ],

            'kode_pos' => [
                'required',
                'string',
                'max:10'
            ],

            'is_primary' => [
                'sometimes',
                'boolean'
            ],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'Validasi gagal',
                $validator->errors(),
                422
            );
        }

        $data = $validator->validated();
        $user = $request->user();

        if (($data['is_primary'] ?? false) === true) {
            $user->alamatPengiriman()
                ->update([
                    'is_primary' => false
                ]);
        }

        $address = $user
            ->alamatPengiriman()
            ->create($data);

        return $this->successResponse(
            $address,
            'Alamat berhasil ditambahkan',
            201
        );
    }

    public function update(
        Request $request,
        int $id
    ) {
        $address = AlamatPengiriman::where(
            'id_user',
            $request->user()->id_user
        )
        ->where('id_alamat', $id)
        ->first();

        if (!$address) {
            return $this->errorResponse(
                'Alamat tidak ditemukan',
                null,
                404
            );
        }

        $validator = Validator::make($request->all(), [
            'label_alamat' => ['sometimes', 'string', 'max:100'],
            'nama_penerima' => ['sometimes', 'string', 'max:150'],
            'nomor_telepon' => ['sometimes', 'string', 'max:30'],
            'alamat_lengkap' => ['sometimes', 'string'],
            'kota' => ['sometimes', 'string', 'max:100'],
            'provinsi' => ['sometimes', 'string', 'max:100'],
            'kode_pos' => ['sometimes', 'string', 'max:10'],
            'is_primary' => ['sometimes', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'Validasi gagal',
                $validator->errors(),
                422
            );
        }

        $data = $validator->validated();

        if (($data['is_primary'] ?? false) === true) {
            $request->user()
                ->alamatPengiriman()
                ->where('id_alamat', '!=', $id)
                ->update([
                    'is_primary' => false
                ]);
        }

        $address->update($data);

        return $this->successResponse(
            $address->fresh(),
            'Alamat berhasil diperbarui'
        );
    }

    public function destroy(
        Request $request,
        int $id
    ) {
        $address = AlamatPengiriman::where(
            'id_user',
            $request->user()->id_user
        )
        ->where('id_alamat', $id)
        ->first();

        if (!$address) {
            return $this->errorResponse(
                'Alamat tidak ditemukan',
                null,
                404
            );
        }

        // Jangan hapus alamat yang sudah direferensikan
        // oleh pesanan lama.
        if ($address->pesanan()->exists()) {
            return $this->errorResponse(
                'Alamat tidak dapat dihapus karena sudah digunakan pada pesanan',
                null,
                409
            );
        }

        $address->delete();

        return $this->successResponse(
            null,
            'Alamat berhasil dihapus'
        );
    }
}