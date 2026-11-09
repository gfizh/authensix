<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Merek;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Menampilkan seluruh produk.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['all', 'active', 'inactive'])],
        ]);

        $products = Produk::query()
            ->with(['kategori', 'merek'])
            ->when(
                filled($filters['search'] ?? null),
                function ($query) use ($filters) {
                    $search = '%' . $filters['search'] . '%';

                    $query->where(function ($subQuery) use ($search) {
                        $subQuery->where('nama_produk', 'like', $search)
                            ->orWhere('sku', 'like', $search);
                    });
                }
            )
            ->when(
                ($filters['status'] ?? 'all') === 'active',
                fn ($query) => $query->where('is_active', true)
            )
            ->when(
                ($filters['status'] ?? 'all') === 'inactive',
                fn ($query) => $query->where('is_active', false)
            )
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Menampilkan form tambah produk.
     */
    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Kategori::query()->get(),
            'brands' => Merek::query()->get(),
        ]);
    }

    /**
     * Menyimpan produk baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        if ($request->hasFile('gambar')) {
            $data['gambar_path'] = $request->file('gambar')
                ->store('products', 'public');
        }

        unset($data['gambar']);

        // Nilai awal dikelola oleh sistem.
        $data['jumlah_terjual'] = 0;
        $data['rating_rata_rata'] = 0;
        $data['is_active'] = true;

        Produk::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit produk.
     */
    public function edit(Produk $produk): View
    {
        return view('admin.products.edit', [
            'product' => $produk,
            'categories' => Kategori::query()->get(),
            'brands' => Merek::query()->get(),
        ]);
    }

    /**
     * Memperbarui data produk.
     */
    public function update(
        Request $request,
        Produk $produk
    ): RedirectResponse {
        $data = $request->validate($this->rules($produk));

        unset($data['gambar']);

        $oldImagePath = $produk->gambar_path;
        $newImagePath = null;

        if ($request->hasFile('gambar')) {
            $newImagePath = $request->file('gambar')
                ->store('products', 'public');

            $data['gambar_path'] = $newImagePath;
        }

        $produk->update($data);

        // Hapus foto lama setelah data berhasil diperbarui.
        if ($newImagePath && $oldImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Mengaktifkan atau menonaktifkan produk.
     */
    public function updateStatus(Produk $produk): RedirectResponse
    {
        $produk->update([
            'is_active' => ! $produk->is_active,
        ]);

        $status = $produk->is_active
            ? 'diaktifkan'
            : 'dinonaktifkan';

        return back()->with(
            'success',
            "Produk berhasil {$status}."
        );
    }

    /**
     * Aturan validasi produk.
     */
    private function rules(?Produk $produk = null): array
    {
        $skuRule = Rule::unique('produk', 'sku');

        if ($produk) {
            $skuRule->ignore($produk->id_produk, 'id_produk');
        }

        return [
            'id_kategori' => [
                'required',
                'integer',
                'exists:kategori,id_kategori',
            ],

            'id_merek' => [
                'required',
                'integer',
                'exists:merek,id_merek',
            ],

            'sku' => [
                'required',
                'string',
                'max:255',
                $skuRule,
            ],

            'nama_produk' => [
                'required',
                'string',
                'max:255',
            ],

            'tipe_produk' => [
                'required',
                Rule::in(['regular', 'prebuild']),
            ],

            'harga' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stok' => [
                'required',
                'integer',
                'min:0',
            ],

            'kondisi' => [
                'required',
                Rule::in(['baru', 'bekas']),
            ],

            'masa_garansi_bulan' => [
                'required',
                'integer',
                'min:0',
            ],

            'deskripsi' => [
                'required',
                'string',
            ],

            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }
}
