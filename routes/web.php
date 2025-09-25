<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ColorManagementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes pour le thème dynamique
Route::get('/theme/colors.css', [ThemeController::class, 'colors'])->name('theme.colors');
Route::get('/api/theme/colors', [ThemeController::class, 'colorsJson'])->name('api.theme.colors');

Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{collection:slug}', [CollectionController::class, 'show'])->name('collections.show');

Route::get('/art/{artwork:slug}', [ArtworkController::class, 'show'])->name('artworks.show');

Route::post('/cart/add/{artwork}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AccountController::class, 'orderDetail'])->name('orders.show');
    Route::get('/invoices/{order}.pdf', [AccountController::class, 'invoice'])->name('invoices.pdf');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/webhooks/stripe', [CheckoutController::class, 'stripeWebhook'])->name('webhooks.stripe');
Route::post('/webhooks/paypal', [CheckoutController::class, 'paypalWebhook'])->name('webhooks.paypal');

// Routes Admin pour les couleurs
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/colors', [ColorController::class, 'index'])->name('colors.index');
    Route::patch('/colors', [ColorController::class, 'update'])->name('colors.update');
    Route::post('/colors/preset', [ColorController::class, 'applyPreset'])->name('colors.preset');
});

require __DIR__.'/auth.php';
