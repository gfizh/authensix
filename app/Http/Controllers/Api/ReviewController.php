<?php

namespace App\Http\Controllers\Api;

use App\Models\Produk;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController
{
    use ApiResponse;

    public function index(int $productId)
    {
        $product = Produk::find($productId);

        if (!$product) {
            return $this->errorResponse(
                'Produk tidak ditemukan',
                null,
                404
            );
        }

        $reviews = Ulasan::with(
            'user:id_user,nama_lengkap'
        )
        ->where(
            'id_produk',
            $productId
        )
        ->latest('id_ulasan')
        ->get();

        return $this->successResponse(
            $reviews,
            'Data ulasan berhasil diambil'
        );
    }

    public function store(
        Request $request,
        int $productId
    ) {
        $user = $request->user();

        $product = Produk::find($productId);

        if (!$product) {
            return $this->errorResponse(
                'Produk tidak ditemukan',
                null,
                404
            );
        }

        $validator = Validator::make(
            $request->all(),
            [
                'rating' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:5',
                ],

                'komentar' => [
                    'required',
                    'string',
                    'max:1000',
                ],
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse(
                'Validasi gagal',
                $validator->errors(),
                422
            );
        }

        $alreadyReviewed = Ulasan::where(
            'id_user',
            $user->id_user
        )
        ->where(
            'id_produk',
            $productId
        )
        ->exists();

        if ($alreadyReviewed) {
            return $this->errorResponse(
                'Anda sudah memberikan ulasan untuk produk ini',
                null,
                409
            );
        }

        $review = Ulasan::create([
            'id_user' =>
                $user->id_user,

            'id_produk' =>
                $productId,

            'rating' =>
                $request->rating,

            'komentar' =>
                $request->komentar,
        ]);

        // Hitung ulang rating produk
        $average = Ulasan::where(
            'id_produk',
            $productId
        )->avg('rating');

        $product->update([
            'rating_rata_rata' =>
                round($average, 2),
        ]);

        $review->load(
            'user:id_user,nama_lengkap'
        );

        return $this->successResponse(
            $review,
            'Ulasan berhasil ditambahkan',
            201
        );
    }
}