<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/products');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');

    // ---- DISCOUNT ---- //
    Route::resource('/discounts', DiscountController::class)->except(['show', 'destroy']);
    Route::delete('/discounts', [DiscountController::class, 'destroy'])->name('discounts.destroy');

    // ----- BRAND ----- //
    Route::resource('/brands', BrandController::class)->except(['show', 'destroy']);
    Route::delete('/brands', [BrandController::class, 'destroy'])->name('brands.destroy');

    // ---- PRODUCT --- //
    Route::resource('/products', ProductController::class)->except(['show', 'destroy']);
    Route::delete('/products', [ProductController::class, 'destroy'])->name('products.destroy');
});

require __DIR__ . '/auth.php';
