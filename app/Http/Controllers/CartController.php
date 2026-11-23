<?php

namespace App\Http\Controllers;

use App\Models\ItemKeranjang;
use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Keranjang::firstOrCreate([
            'id_user' => $request->user()->id_user,
        ]);

        $cart->load([
            'items.produk.kategori',
            'items.produk.merek',
        ]);

        $validItems = collect();

        foreach ($cart->items as $item) {
            if (! $item->produk) {
                $item->delete();

                continue;
            }

            $product = $item->produk;
            $varianParts = array_filter([
                $product->kategori?->nama_kategori,
                $product->merek?->nama_merek,
            ]);

            $validItems->push((object) [
                'id' => $item->id_item_keranjang,
                'id_produk' => $product->id_produk,
                'nama' => $product->nama_produk,
                'varian' => ! empty($varianParts) ? implode(' · ', $varianParts) : null,
                'harga' => (int) round((float) $product->harga),
                'qty' => (int) $item->jumlah,
                'stok' => (int) $product->stok,
                'gambar_url' => $product->gambar_url,
                'gambar_path' => $product->gambar_path,
                'is_active' => (bool) $product->is_active,
            ]);
        }

        return view('users.cart', [
            'items' => $validItems,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => ['required', 'integer', 'exists:produk,id_produk'],
            'jumlah' => ['nullable', 'integer', 'min:1'],
            'qty' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = (int) ($validated['jumlah'] ?? $validated['qty'] ?? 1);
        $product = Produk::find($validated['id_produk']);

        if (! $product || ! $product->is_active) {
            $message = 'Produk tidak tersedia atau sudah dinonaktifkan.';

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->with('error', $message);
        }

        if ($product->stok <= 0) {
            $message = 'Stok produk sedang habis.';

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->with('error', $message);
        }

        $cart = Keranjang::firstOrCreate([
            'id_user' => $request->user()->id_user,
        ]);

        $item = ItemKeranjang::where('id_keranjang', $cart->id_keranjang)
            ->where('id_produk', $product->id_produk)
            ->first();

        $newQuantity = ($item?->jumlah ?? 0) + $quantity;

        if ($newQuantity > $product->stok) {
            $message = "Jumlah melebihi stok tersedia ({$product->stok}).";

            return $request->expectsJson()
                ? response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => ['stok_tersedia' => $product->stok],
                ], 422)
                : back()->with('error', $message);
        }

        if ($item) {
            $item->update([
                'jumlah' => $newQuantity,
                'harga_satuan' => $product->harga,
            ]);
        } else {
            $item = ItemKeranjang::create([
                'id_keranjang' => $cart->id_keranjang,
                'id_produk' => $product->id_produk,
                'jumlah' => $quantity,
                'harga_satuan' => $product->harga,
            ]);
        }

        $cartCount = (int) $cart->items()->sum('jumlah');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang.',
                'data' => $item->fresh('produk'),
                'cart_count' => $cartCount,
            ], 201);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, int $id)
    {
        $item = $this->findUserCartItem($request->user()->id_user, $id);

        if (! $item || ! $item->produk) {
            $message = 'Item keranjang tidak ditemukan.';

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 404)
                : back()->with('error', $message);
        }

        $rawQuantity = $request->input('btn_qty') ?? $request->input('qty') ?? $request->input('jumlah');

        if (! is_numeric($rawQuantity) || (int) $rawQuantity < 1) {
            $message = 'Jumlah produk minimal 1.';

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->with('error', $message);
        }

        $quantity = (int) $rawQuantity;

        if (! $item->produk->is_active || $quantity > $item->produk->stok) {
            $message = ! $item->produk->is_active
                ? 'Produk sudah tidak tersedia.'
                : "Jumlah melebihi stok tersedia ({$item->produk->stok}).";

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->with('error', $message);
        }

        $item->update([
            'jumlah' => $quantity,
            'harga_satuan' => $item->produk->harga,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Jumlah produk berhasil diperbarui.',
                'data' => $item->fresh('produk'),
            ]);
        }

        return back()->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id)
    {
        $item = $this->findUserCartItem($request->user()->id_user, $id);

        if (! $item) {
            $message = 'Item keranjang tidak ditemukan.';

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 404)
                : back()->with('error', $message);
        }

        $item->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari keranjang.',
            ]);
        }

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear(Request $request)
    {
        $cart = Keranjang::where('id_user', $request->user()->id_user)->first();

        if ($cart) {
            $cart->items()->delete();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan.',
            ]);
        }

        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }

    private function findUserCartItem(int $userId, int $itemId): ?ItemKeranjang
    {
        return ItemKeranjang::with('produk')
            ->where('id_item_keranjang', $itemId)
            ->whereHas('keranjang', function ($query) use ($userId) {
                $query->where('id_user', $userId);
            })
            ->first();
    }
}
