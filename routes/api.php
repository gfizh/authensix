<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CartItemController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ShippingController;
use App\Http\Controllers\Api\ReviewController;

// AUTHENTICATION
Route::post('/login', [AuthController::class, 'login']);

// KATALOG PRODUK (PUBLIC)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{id}', [BrandController::class, 'show']);

Route::get(
    '/products/{productId}/reviews',
    [ReviewController::class, 'index']
);

// ROUTE KHUSUS PENGGUNA LOGIN
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // KERANJANG
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartItemController::class, 'store']);
    Route::patch('/cart/items/{id}', [CartItemController::class, 'update']);
    Route::delete('/cart/items/{id}', [CartItemController::class, 'destroy']);

    // ALAMAT PENGIRIMAN
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::patch('/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);

    // CHECKOUT
    Route::post('/checkout', [CheckoutController::class, 'store']);

    // PESANAN
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // PEMBAYARAN
    Route::post(
        '/orders/{orderId}/payment',
        [PaymentController::class, 'store']
    );

    // INVOICE
    Route::get(
        '/orders/{orderId}/invoice',
        [InvoiceController::class, 'show']
    );

    // PENGIRIMAN
    Route::get(
        '/orders/{orderId}/shipping',
        [ShippingController::class, 'show']
    );

    // ULASAN
    Route::post(
        '/products/{productId}/reviews',
        [ReviewController::class, 'store']
    );
});
