<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'integer', 'exists:kategori,id_kategori'],
        ]);

        $products = Produk::query()
            ->with(['kategori', 'merek'])
            ->withCount('ulasan')
            ->where('is_active', true)
            ->when(
                filled($filters['search'] ?? null),
                fn ($query) => $query->where(function ($query) use ($filters) {
                    $search = '%'.$filters['search'].'%';

                    $query->where('nama_produk', 'like', $search)
                        ->orWhere('sku', 'like', $search);
                })
            )
            ->when(
                filled($filters['category'] ?? null),
                fn ($query) => $query->where('id_kategori', $filters['category'])
            )
            ->orderByDesc('id_produk')
            ->paginate(8)
            ->withQueryString();

        return view('dashboard', [
            'categories' => Kategori::query()->orderBy('nama_kategori')->get(),
            'products' => $products,
        ]);
    }
}
