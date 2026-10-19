<?php

namespace App\Http\Controllers\Api;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProductController
{
    use ApiResponse;

    public function index(Request $request)
    {
        $query = Produk::with([
            'kategori',
            'merek',
            'spesifikasi',
        ])->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'nama_produk',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'sku',
                    'like',
                    "%{$search}%"
                );
            });
        }

        if ($request->filled('id_kategori')) {
            $query->where(
                'id_kategori',
                $request->id_kategori
            );
        }

        if ($request->filled('id_merek')) {
            $query->where(
                'id_merek',
                $request->id_merek
            );
        }

        if ($request->filled('tipe_produk')) {
            $query->where(
                'tipe_produk',
                $request->tipe_produk
            );
        }

        if ($request->filled('kondisi')) {
            $query->where(
                'kondisi',
                $request->kondisi
            );
        }

        $perPage = min(
            max((int) $request->get('per_page', 10), 1),
            50
        );

        $products = $query
            ->latest('id_produk')
            ->paginate($perPage);

        return $this->successResponse([
            'items' => $products->items(),

            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ], 'Data produk berhasil diambil');
    }

    public function show(int $id)
    {
        $product = Produk::with([
            'kategori',
            'merek',
            'spesifikasi',
            'ulasan.user',
            'prebuildParent.child',
        ])->find($id);

        if (!$product) {
            return $this->errorResponse(
                'Produk tidak ditemukan',
                null,
                404
            );
        }

        return $this->successResponse(
            $product,
            'Detail produk berhasil diambil'
        );
    }
}