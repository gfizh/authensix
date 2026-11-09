<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merek;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BrandController extends Controller
{
    /**
     * Menampilkan seluruh merek.
     */
    public function index(): View
    {
        $brands = Merek::query()
            ->orderBy('nama_merek')
            ->paginate(10);

        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Menampilkan form tambah merek.
     */
    public function create(): View
    {
        return view('admin.brands.create');
    }

    /**
     * Menyimpan merek baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        Merek::create($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Merek berhasil ditambahkan.');
    }

    /**
     * Menampilkan merek yang akan diedit.
     */
    public function edit(Merek $merek): View
    {
        return view('admin.brands.edit', [
            'brand' => $merek,
        ]);
    }

    /**
     * Memperbarui merek.
     */
    public function update(
        Request $request,
        Merek $merek
    ): RedirectResponse {
        $data = $request->validate($this->rules($merek));

        $merek->update($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Merek berhasil diperbarui.');
    }

    /**
     * Menghapus merek jika tidak digunakan produk.
     */
    public function destroy(Merek $merek): RedirectResponse
    {
        $hasProducts = Produk::where(
            'id_merek',
            $merek->id_merek
        )->exists();

        if ($hasProducts) {
            return back()->with(
                'error',
                'Merek tidak dapat dihapus karena masih digunakan produk.'
            );
        }

        $merek->delete();

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Merek berhasil dihapus.');
    }

    /**
     * Aturan validasi merek.
     */
    private function rules(?Merek $merek = null): array
    {
        $namaRule = Rule::unique('merek', 'nama_merek');

        if ($merek) {
            $namaRule->ignore($merek->id_merek, 'id_merek');
        }

        return [
            'nama_merek' => [
                'required',
                'string',
                'max:255',
                $namaRule,
            ],

            'logo_url' => [
                'required',
                'url',
                'max:255',
            ],
        ];
    }
}
