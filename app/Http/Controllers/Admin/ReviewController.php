<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Menampilkan seluruh ulasan.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'rating' => [
                'nullable',
                Rule::in([1, 2, 3, 4, 5]),
            ],
        ]);

        $search = trim($validated['search'] ?? '');
        $rating = $validated['rating'] ?? null;

        $ulasanList = Ulasan::with([
            'user',
            'produk',
        ])
            ->when($rating !== null, function ($query) use ($rating) {
                $query->where('rating', $rating);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    // Pencarian berdasarkan pelanggan.
                    $q->whereHas('user', function ($user) use ($search) {
                        $user->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                    })

                    // Pencarian berdasarkan produk.
                    ->orWhereHas('produk', function ($produk) use ($search) {
                        $produk->where(
                            'nama_produk',
                            'like',
                            "%{$search}%"
                        );
                    });
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.reviews.index', compact(
            'ulasanList',
            'search',
            'rating'
        ));
    }

    /**
     * Menampilkan detail ulasan.
     */
    public function show(Ulasan $ulasan): View
    {
        $ulasan->load([
            'user',
            'produk',
        ]);

        return view('admin.reviews.show', compact('ulasan'));
    }

    /**
     * Menghapus ulasan dan memperbarui rating produk.
     */
    public function destroy(Ulasan $ulasan): RedirectResponse
    {
        DB::transaction(function () use ($ulasan) {

            // Kunci produk agar perhitungan rating tidak
            // bertabrakan dengan proses lain yang mengubah rating.
            $produk = Produk::query()
                ->whereKey($ulasan->id_produk)
                ->lockForUpdate()
                ->firstOrFail();

            // Ambil dan kunci ulang ulasan.
            $ulasan = Ulasan::query()
                ->whereKey($ulasan->id_ulasan)
                ->lockForUpdate()
                ->firstOrFail();

            $ulasan->delete();

            // Hitung ulang rata-rata rating produk.
            $rataRata = Ulasan::where(
                'id_produk',
                $produk->id_produk
            )->avg('rating');

            // Jika tidak ada ulasan, rating kembali menjadi 0.
            $produk->update([
                'rating_rata_rata' => round((float) ($rataRata ?? 0), 2),
            ]);
        });

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}
