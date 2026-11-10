<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Merek;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Menampilkan seluruh produk.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if (! view()->exists('admin.products.index')) {
            return redirect()->route('admin.dashboard-admin');
        }

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['all', 'active', 'inactive'])],
        ]);

        $products = Produk::query()
            ->with(['kategori', 'merek'])
            ->when(
                filled($filters['search'] ?? null),
                function ($query) use ($filters) {
                    $search = '%'.$filters['search'].'%';

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
        $viewName = view()->exists('admin.tambah_produk')
            ? 'admin.tambah_produk'
            : 'admin.products.create';

        return view($viewName, [
            'categories' => Kategori::query()
                ->orderBy('nama_kategori')
                ->get(),

            'brands' => Merek::query()
                ->orderBy('nama_merek')
                ->get(),
        ]);
    }

    /**
     * Menyimpan produk baru.
     */
    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Default Field
        |--------------------------------------------------------------------------
        */

        $defaults = [
            'tipe_produk' => 'regular',
            'kondisi' => 'baru',
            'masa_garansi_bulan' => 12,
        ];

        foreach ($defaults as $field => $value) {
            if (! $request->filled($field)) {
                $request->merge([
                    $field => $value,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Kompatibilitas Input Berat
        |--------------------------------------------------------------------------
        |
        | Frontend saat ini menggunakan name="berat".
        | Database menggunakan kolom berat_gram.
        |
        */

        if (! $request->has('berat_gram') && $request->has('berat')) {
            $request->merge([
                'berat_gram' => $request->input('berat'),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Validasi
        |--------------------------------------------------------------------------
        */

        $data = $request->validate($this->rules());

        /*
        |--------------------------------------------------------------------------
        | 4. Generate SKU Otomatis
        |--------------------------------------------------------------------------
        */

        if (blank($data['sku'] ?? null)) {
            $data['sku'] = $this->generateSku(
                $data['nama_produk']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Default Harga Diskon dan Berat
        |--------------------------------------------------------------------------
        */

        $data['harga_diskon'] = $data['harga_diskon'] ?? null;
        $data['berat_gram'] = $data['berat_gram'] ?? 0;

        /*
        |--------------------------------------------------------------------------
        | 6. Upload Gambar
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('gambar')) {
            $data['gambar_path'] = $request->file('gambar')
                ->store('products', 'public');
        }

        unset($data['gambar']);

        /*
        |--------------------------------------------------------------------------
        | 7. Nilai Awal Sistem
        |--------------------------------------------------------------------------
        */

        $data['jumlah_terjual'] = 0;
        $data['rating_rata_rata'] = 0;
        $data['is_active'] = true;

        /*
        |--------------------------------------------------------------------------
        | 8. Simpan Produk
        |--------------------------------------------------------------------------
        */

        Produk::create($data);

        return redirect()
            ->route('admin.dashboard-admin')
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
        /*
        |--------------------------------------------------------------------------
        | 1. Kompatibilitas Input Berat
        |--------------------------------------------------------------------------
        */

        if (! $request->has('berat_gram') && $request->has('berat')) {
            $request->merge([
                'berat_gram' => $request->input('berat'),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Validasi
        |--------------------------------------------------------------------------
        */

        $data = $request->validate($this->rules($produk));

        /*
        |--------------------------------------------------------------------------
        | 3. Upload Gambar Baru
        |--------------------------------------------------------------------------
        */

        unset($data['gambar']);

        $oldImagePath = $produk->gambar_path;
        $newImagePath = null;

        if ($request->hasFile('gambar')) {
            $newImagePath = $request->file('gambar')
                ->store('products', 'public');

            $data['gambar_path'] = $newImagePath;
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Update Produk
        |--------------------------------------------------------------------------
        */

        $produk->update($data);

        /*
        |--------------------------------------------------------------------------
        | 5. Hapus Gambar Lama
        |--------------------------------------------------------------------------
        */

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
     * Membuat SKU otomatis.
     *
     * Format: PRD-NAMAPRODUK-XXX
     */
    private function generateSku(string $namaProduk): string
    {
        $cleanName = preg_replace(
            '/[^A-Za-z0-9]/',
            '',
            substr($namaProduk, 0, 8)
        );

        $cleanName = strtoupper($cleanName ?: 'PRD');

        do {
            $sku = 'PRD-'.$cleanName.'-'.random_int(100, 999);
        } while (Produk::where('sku', $sku)->exists());

        return $sku;
    }

    /**
     * Aturan validasi produk.
     */
    private function rules(?Produk $produk = null): array
    {
        $skuRule = Rule::unique('produk', 'sku');

        if ($produk) {
            $skuRule->ignore(
                $produk->id_produk,
                'id_produk'
            );
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

            'sku' => $produk
                ? [
                    'required',
                    'string',
                    'max:255',
                    $skuRule,
                ]
                : [
                    'nullable',
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

            'harga_diskon' => [
                'nullable',
                'numeric',
                'min:0',
                'lt:harga',
            ],

            'stok' => [
                'required',
                'integer',
                'min:0',
            ],

            'berat_gram' => [
                'nullable',
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