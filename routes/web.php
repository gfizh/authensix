<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Models\Kategori;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::view('/login', 'login')
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');

Route::get('/dashboard', DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

Route::view('/produk/{id}', 'detail-produk')
    ->whereNumber('id')
    ->middleware('auth')
    ->name('produk.detail');

Route::view('/checkout', 'checkout')
    ->middleware('auth')
    ->name('checkout');

Route::get('/checkout/berhasil', function (Request $request) {
    $orderId = $request->query('order_id');
    $orderNumber = $request->query('order');

    $query = Pesanan::with([
        'items.produk',
        'invoice',
        'pembayaran',
        'pengiriman',
        'alamat',
    ])
        ->where('id_user', auth()->id());

    if ($orderId) {
        $order = (clone $query)->where('id_pesanan', $orderId)->first();
    } elseif ($orderNumber) {
        $order = (clone $query)->where('nomor_pesanan', $orderNumber)->first();
    } else {
        $order = null;
    }

    if (! $order) {
        $order = $query->latest('id_pesanan')->first();
    }

    return view('checkout-berhasil', [
        'order' => $order,
    ]);
})
    ->middleware('auth')
    ->name('checkout.success');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/kategori', function () {
    $categories = Kategori::query()
        ->with([
            'produk' => fn ($query) => $query
                ->where('is_active', true)
                ->with('merek')
                ->orderBy('nama_produk'),
        ])
        ->whereHas('produk', fn ($query) => $query->where('is_active', true))
        ->orderBy('nama_kategori')
        ->get();

    return view('kategori', [
        'categories' => $categories,
        'emptyCategories' => [
            'Laptop',
            'Monitor',
            'Peripheral',
            'Networking',
            'Aksesoris Komputer',
            'Printer & Scanner',
        ],
    ]);
})
    ->middleware('auth')
    ->name('kategori');

Route::get('/keranjang', function () {
    $items = collect([
        ['id' => 1, 'nama' => 'GIGABYTE VGA NVIDIA GEFORCE RTX 5060 TI EAGLE ICE 16GB GDDR7 OC WHITE', 'varian' => '16GB GDDR7 OC', 'harga' => 12430000, 'qty' => 1, 'stok' => 1],
        ['id' => 2, 'nama' => 'Hardisk PC/Laptop 160GB SATA', 'varian' => null, 'harga' => 250000, 'qty' => 2, 'stok' => 10],
        ['id' => 3, 'nama' => 'Monitor PC 24inch Multimedia', 'varian' => 'Hitam', 'harga' => 1500000, 'qty' => 1, 'stok' => 4],
    ])->map(fn ($i) => (object) $i);

    return view('cart', ['items' => $items]);
})->name('cart.index');

// Route placeholder supaya route('...') di Blade tidak error.
// Belum menyimpan apa-apa, hanya kembali ke halaman keranjang.
Route::patch('/keranjang/{id}', fn () => back())->name('cart.update');
Route::delete('/keranjang/{id}', fn () => back())->name('cart.destroy');
Route::delete('/keranjang', fn () => back())->name('cart.clear');
Route::post('/checkout', fn () => back())->name('checkout');
