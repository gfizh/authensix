<?php

namespace App\Http\Controllers\Api;

use App\Models\Merek;

class BrandController
{
    use ApiResponse;

    public function index()
    {
        $brands = Merek::withCount('produk')->get();

        return $this->successResponse(
            $brands,
            'Data merek berhasil diambil'
        );
    }

    public function show(int $id)
    {
        $brand = Merek::with('produk')->find($id);

        if (!$brand) {
            return $this->errorResponse(
                'Merek tidak ditemukan',
                null,
                404
            );
        }

        return $this->successResponse(
            $brand,
            'Detail merek berhasil diambil'
        );
    }
}