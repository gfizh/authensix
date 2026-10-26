<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Models\Kategori;
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

Route::view('/checkout/berhasil', 'checkout-berhasil')
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