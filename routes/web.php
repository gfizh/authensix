<?php

use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderHistoryController;
use App\Models\Kategori;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =====================================================
// PUBLIC & AUTHENTICATION
// =====================================================

Route::get('/', function (Request $request) {
    if ($request->user()?->isAdmin()) {
        return redirect()->route('admin.dashboard-admin');
    }

    return redirect()->route('login');
})->name('home');

Route::view('/login', 'login')
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// =====================================================
// CUSTOMER DASHBOARD
// =====================================================

Route::get('/dashboard', DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

// =====================================================
// PRODUCT DETAIL & CATEGORY
// =====================================================

Route::view('/produk/{id}', 'users.detail-produk')
    ->whereNumber('id')
    ->middleware('auth')
    ->name('produk.detail');

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

    return view('users.kategori', [
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

// =====================================================
// CART
// =====================================================

Route::get('/keranjang', function () {
    $items = collect([
        [
            'id' => 1,
            'nama' => 'GIGABYTE VGA NVIDIA GEFORCE RTX 5060 TI EAGLE ICE 16GB GDDR7 OC WHITE',
            'varian' => '16GB GDDR7 OC',
            'harga' => 12430000,
            'qty' => 1,
            'stok' => 1,
        ],
        [
            'id' => 2,
            'nama' => 'Hardisk PC/Laptop 160GB SATA',
            'varian' => null,
            'harga' => 250000,
            'qty' => 2,
            'stok' => 10,
        ],
        [
            'id' => 3,
            'nama' => 'Monitor PC 24inch Multimedia',
            'varian' => 'Hitam',
            'harga' => 1500000,
            'qty' => 1,
            'stok' => 4,
        ],
    ])->map(fn ($item) => (object) $item);

    return view('users.cart', ['items' => $items]);
})
    ->middleware('auth')
    ->name('cart.index');

Route::patch('/keranjang/{id}', fn () => back())
    ->middleware('auth')
    ->name('cart.update');

Route::delete('/keranjang/{id}', fn () => back())
    ->middleware('auth')
    ->name('cart.destroy');

Route::delete('/keranjang', fn () => back())
    ->middleware('auth')
    ->name('cart.clear');

// =====================================================
// CHECKOUT
// =====================================================

Route::view('/checkout', 'users.checkout')
    ->middleware('auth')
    ->name('checkout');

Route::post('/checkout', fn () => back())
    ->middleware('auth')
    ->name('checkout.store');

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
        ->where('id_user', Auth::id());

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

    return view('users.checkout-berhasil', [
        'order' => $order,
    ]);
})
    ->middleware('auth')
    ->name('checkout.success');

// =====================================================
// CUSTOMER ORDER HISTORY
// =====================================================

Route::get('/riwayat-pesanan', [OrderHistoryController::class, 'index'])
    ->middleware('auth')
    ->name('riwayat-pesanan');

Route::get('/users/riwayat-pesanan', fn () => redirect()->route('riwayat-pesanan'))
    ->middleware('auth');

Route::patch('/riwayat-pesanan/{pesanan}/batal', [OrderHistoryController::class, 'cancel'])
    ->middleware('auth')
    ->name('riwayat-pesanan.cancel');

// =====================================================
// ADMIN MANAGEMENT
// =====================================================

Route::get('/dashboard-admin', function () {
    return redirect()->route('admin.dashboard-admin');
})->middleware(['auth', 'admin'])->name('dashboard.admin');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', AdminDashboardController::class)
            ->name('dashboard');

        Route::get('/dashboard-admin', AdminDashboardController::class)
            ->name('dashboard-admin');

        // Manajemen Pesanan & Produk
        Route::get('/manajemen_produk', [AdminOrderController::class, 'index'])
            ->name('manajemen_produk');

        Route::get('/manajemen-pesanan', fn () => redirect()->route('admin.manajemen_produk'))
            ->name('manajemen-pesanan');

        // Product Management
        Route::get('/tambah_produk', [AdminProductController::class, 'create'])
            ->name('tambah_produk');

        Route::resource('products', AdminProductController::class)
            ->except(['show', 'destroy'])
            ->parameters(['products' => 'produk']);

        Route::patch('/products/{produk}/status', [
            AdminProductController::class,
            'updateStatus',
        ])->name('products.status');

        // Category Management
        Route::resource('categories', AdminCategoryController::class)
            ->except(['show'])
            ->parameters(['categories' => 'kategori']);

        // Brand Management
        Route::resource('brands', AdminBrandController::class)
            ->except(['show'])
            ->parameters(['brands' => 'merek']);

        // Order Management
        Route::get('/orders', [AdminOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{pesanan}', [AdminOrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('/orders/{pesanan}/status', [
            AdminOrderController::class,
            'updateStatus',
        ])->name('orders.status');

        Route::post('/orders/{pesanan}/shipment', [
            AdminOrderController::class,
            'storeShipment',
        ])->name('orders.shipment.store');

        Route::patch('/orders/{pesanan}/shipment', [
            AdminOrderController::class,
            'updateShipment',
        ])->name('orders.shipment.update');

        // User Management
        Route::resource('users', AdminUserController::class)
            ->only(['index', 'show', 'edit', 'update']);

        // Shipment Management
        Route::resource('shipments', ShipmentController::class)
            ->only(['index', 'show', 'store', 'update'])
            ->parameters(['shipments' => 'pengiriman']);

        Route::patch('/shipments/{pengiriman}/status', [
            ShipmentController::class,
            'updateStatus',
        ])->name('shipments.status');

        // Review Management
        Route::resource('reviews', ReviewController::class)
            ->only(['index', 'show', 'destroy'])
            ->parameters(['reviews' => 'ulasan']);

        // Payment Management
        Route::resource('payments', PaymentController::class)
            ->only(['index', 'show'])
            ->parameters(['payments' => 'pembayaran']);

        Route::patch('/payments/{pembayaran}/status', [
            PaymentController::class,
            'updateStatus',
        ])->name('payments.status');

    });
