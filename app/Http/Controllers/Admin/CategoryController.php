<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Menampilkan seluruh kategori.
     */
    public function index(): View
    {
        $categories = Kategori::query()
            ->orderBy('nama_kategori')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menampilkan form tambah kategori.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Menyimpan kategori baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
            ],
            'deskripsi' => [
                'required',
                'string',
            ],
        ]);

        $data['slug'] = $this->generateSlug(
            $data['nama_kategori']
        );

        Kategori::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Menampilkan kategori yang akan diedit.
     */
    public function edit(Kategori $kategori): View
    {
        return view('admin.categories.edit', [
            'category' => $kategori,
        ]);
    }

    /**
     * Memperbarui kategori.
     */
    public function update(
        Request $request,
        Kategori $kategori
    ): RedirectResponse {
        $data = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
            ],
            'deskripsi' => [
                'required',
                'string',
            ],
        ]);

        // Generate slug baru jika nama kategori berubah.
        if ($kategori->nama_kategori !== $data['nama_kategori']) {
            $data['slug'] = $this->generateSlug(
                $data['nama_kategori'],
                $kategori->id_kategori
            );
        }

        $kategori->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus kategori jika tidak digunakan produk.
     */
    public function destroy(Kategori $kategori): RedirectResponse
    {
        $hasProducts = Produk::where(
            'id_kategori',
            $kategori->id_kategori
        )->exists();

        if ($hasProducts) {
            return back()->with(
                'error',
                'Kategori tidak dapat dihapus karena masih digunakan produk.'
            );
        }

        $kategori->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Membuat slug unik berdasarkan nama kategori.
     */
    private function generateSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'kategori';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            Kategori::where('slug', $slug)
                ->when(
                    $ignoreId !== null,
                    fn ($query) => $query->where(
                        'id_kategori',
                        '!=',
                        $ignoreId
                    )
                )
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
