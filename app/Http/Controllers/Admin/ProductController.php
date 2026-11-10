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
    public function index(Request $request): View
    {
        if (view()->exists('admin.manajemen_produk')) {
            return app(OrderController::class)->index($request, 'produk');
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
        $viewName = view()->exists('admin.tambah_produk') ? 'admin.tambah_produk' : 'admin.products.create';

        return view($viewName, [
            'categories' => Kategori::query()->orderBy('nama_kategori')->get(),
            'brands' => Merek::query()->orderBy('nama_merek')->get(),
        ]);
    }

    /**
     * Menyimpan produk baru.
     */
    public function store(Request $request): RedirectResponse
    {
        // Lengkapi default field skema yang tidak terdapat di form UI
        if (! $request->filled('sku') && $request->filled('nama_produk')) {
            $cleanName = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', substr($request->nama_produk, 0, 8)));
            $cleanName = $cleanName ?: 'PRD';
            $sku = 'PRD-'.$cleanName.'-'.rand(100, 999);
            while (Produk::where('sku', $sku)->exists()) {
                $sku = 'PRD-'.$cleanName.'-'.rand(100, 999);
            }
            $request->merge(['sku' => $sku]);
        }
        if (! $request->filled('tipe_produk')) {
            $request->merge(['tipe_produk' => 'regular']);
        }
        if (! $request->filled('kondisi')) {
            $request->merge(['kondisi' => 'baru']);
        }
        if (! $request->filled('masa_garansi_bulan')) {
            $request->merge(['masa_garansi_bulan' => 12]);
        }

        $data = $request->validate($this->rules());

        if ($request->hasFile('gambar')) {
            $data['gambar_path'] = $request->file('gambar')
                ->store('products', 'public');
        }

        unset($data['gambar']);
        unset($data['harga_diskon']);
        unset($data['berat']);

        // Nilai awal dikelola oleh sistem.
        $data['jumlah_terjual'] = 0;
        $data['rating_rata_rata'] = 0;
        $data['is_active'] = true;

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
        $viewName = view()->exists('admin.products.edit') ? 'admin.products.edit' : 'admin.tambah_produk';

        return view($viewName, [
            'product' => $produk,
            'categories' => Kategori::query()->orderBy('nama_kategori')->get(),
            'brands' => Merek::query()->orderBy('nama_merek')->get(),
        ]);
    }

    /**
     * Memperbarui data produk.
     */
    public function update(
        Request $request,
        Produk $produk
    ): RedirectResponse {
        $request->merge([
            'sku' => $request->input('sku', $produk->sku),
            'tipe_produk' => $request->input('tipe_produk', $produk->tipe_produk),
            'kondisi' => $request->input('kondisi', $produk->kondisi),
            'masa_garansi_bulan' => $request->input('masa_garansi_bulan', $produk->masa_garansi_bulan),
        ]);

        $data = $request->validate($this->rules($produk));

        unset($data['gambar']);
        unset($data['harga_diskon']);
        unset($data['berat']);

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
            ->route('admin.manajemen_produk', ['tab' => 'produk'])
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
