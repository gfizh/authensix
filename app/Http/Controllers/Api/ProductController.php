<?php

namespace App\Http\Controllers\Api;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController
{
    use ApiResponse;

    /**
     * Menampilkan daftar produk aktif.
     * Mendukung pencarian, filter, dan pagination.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'id_kategori' => ['nullable', 'integer'],
            'id_merek' => ['nullable', 'integer'],
            'tipe_produk' => [
                'nullable',
                Rule::in(['regular', 'prebuild']),
            ],
            'kondisi' => [
                'nullable',
                Rule::in(['baru', 'bekas']),
            ],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $query = Produk::with([
            'kategori',
            'merek',
            'spesifikasi',
        ])->where('is_active', true);

        if (!empty($validated['search'])) {
            $search = $validated['search'];

            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if (isset($validated['id_kategori'])) {
            $query->where('id_kategori', $validated['id_kategori']);
        }

        if (isset($validated['id_merek'])) {
            $query->where('id_merek', $validated['id_merek']);
        }

        if (isset($validated['tipe_produk'])) {
            $query->where('tipe_produk', $validated['tipe_produk']);
        }

        if (isset($validated['kondisi'])) {
            $query->where('kondisi', $validated['kondisi']);
        }

        $perPage = (int) ($validated['per_page'] ?? 10);

        $products = $query
            ->orderByDesc('id_produk')
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

    /**
     * Menampilkan detail satu produk.
     */
    public function show(int $id)
    {
        $product = Produk::with([
            'kategori',
            'merek',
            'spesifikasi',
            'ulasan.user',
            'prebuildParent.child',
        ])
            ->where('is_active', true)
            ->find($id);

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

    /**
     * Menambahkan produk baru beserta gambar.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
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
            'sku' => ['required', 'string', 'max:255', 'unique:produk,sku'],
            'nama_produk' => ['required', 'string', 'max:255'],
            'tipe_produk' => [
                'required',
                Rule::in(['regular', 'prebuild']),
            ],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => [
                'required',
                Rule::in(['baru', 'bekas']),
            ],
            'masa_garansi_bulan' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'max:5120'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        // Nilai awal untuk kolom yang wajib diisi database.
        $validated['jumlah_terjual'] = 0;
        $validated['rating_rata_rata'] = 0;
        $validated['is_active'] = $validated['is_active'] ?? true;

        // Simpan gambar di storage/app/public/products.
        if ($request->hasFile('gambar')) {
            $validated['gambar_path'] = $request
                ->file('gambar')
                ->store('products', 'public');
        }

        $product = Produk::create($validated);

        return $this->successResponse(
            $product->load(['kategori', 'merek', 'spesifikasi']),
            'Produk berhasil ditambahkan'
        );
    }

    /**
     * Memperbarui data produk dan mengganti gambar jika dikirim.
     */
    public function update(Request $request, int $id)
    {
        $product = Produk::find($id);

        if (!$product) {
            return $this->errorResponse(
                'Produk tidak ditemukan',
                null,
                404
            );
        }

        $validated = $request->validate([
            'id_kategori' => [
                'sometimes',
                'required',
                'integer',
                'exists:kategori,id_kategori',
            ],
            'id_merek' => [
                'sometimes',
                'required',
                'integer',
                'exists:merek,id_merek',
            ],
            'sku' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('produk', 'sku')->ignore($product->id_produk, 'id_produk'),
            ],
            'nama_produk' => ['sometimes', 'required', 'string', 'max:255'],
            'tipe_produk' => [
                'sometimes',
                'required',
                Rule::in(['regular', 'prebuild']),
            ],
            'harga' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stok' => ['sometimes', 'required', 'integer', 'min:0'],
            'kondisi' => [
                'sometimes',
                'required',
                Rule::in(['baru', 'bekas']),
            ],
            'masa_garansi_bulan' => [
                'sometimes',
                'required',
                'integer',
                'min:0',
            ],
            'deskripsi' => ['sometimes', 'required', 'string'],
            'gambar' => ['nullable', 'image', 'max:5120'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada.
            if ($product->gambar_path) {
                Storage::disk('public')->delete($product->gambar_path);
            }

            $validated['gambar_path'] = $request
                ->file('gambar')
                ->store('products', 'public');
        }

        $product->update($validated);

        return $this->successResponse(
            $product->fresh()->load(['kategori', 'merek', 'spesifikasi']),
            'Produk berhasil diperbarui'
        );
    }

    /**
     * Menonaktifkan produk agar tidak tampil di katalog.
     */
    public function destroy(int $id)
    {
        $product = Produk::find($id);

        if (!$product) {
            return $this->errorResponse(
                'Produk tidak ditemukan',
                null,
                404
            );
        }

        // Soft deactivation: data produk dan gambar tetap tersimpan.
        $product->update(['is_active' => false]);

        return $this->successResponse(
            null,
            'Produk berhasil dinonaktifkan'
        );
    }
}