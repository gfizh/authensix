<?php

namespace App\Http\Controllers\Api;

use App\Models\Kategori;

class CategoryController
{
    use ApiResponse;

    public function index()
    {
        $categories = Kategori::withCount('produk')->get();

        return $this->successResponse(
            $categories,
            'Data kategori berhasil diambil'
        );
    }

    public function show(int $id)
    {
        $category = Kategori::with('produk')->find($id);

        if (!$category) {
            return $this->errorResponse(
                'Kategori tidak ditemukan',
                null,
                404
            );
        }

        return $this->successResponse(
            $category,
            'Detail kategori berhasil diambil'
        );
    }
}