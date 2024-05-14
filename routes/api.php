<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\DiscountController;
use App\Http\Controllers\API\NewsletterController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductColorController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// --------------------------------------------------------------------------

Route::post('/subscribe', [NewsletterController::class, 'subscribe']);

Route::post('register', [AuthController::class, 'registerUser']);
Route::post('login', [AuthController::class, 'loginUser']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::get('auth/facebook', [AuthController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [AuthController::class, 'handleFacebookCallback']);

Route::get('auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logoutUser']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/cart', [CartController::class, 'getCartItems']);
    Route::post('/add-to-cart/{product}', [CartController::class, 'addItemToCart']);
    Route::delete('/clear-cart', [CartController::class, 'clearCart']);
    Route::delete('/cart/{cartItem}', [CartController::class, 'deleteCartItem']);

    Route::get('/orders', [OrderController::class, 'getAllOrders']);
    Route::post('/orders', [OrderController::class, 'store']);
});

// --------------------------------------------------------------------------

Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{brand}', [BrandController::class, 'show']);

Route::get('/brands/{brand}/products', [ProductController::class, 'getByBrand']);

// --------------------------------------------------------------------------

Route::get('/discounts', [DiscountController::class, 'index']);
Route::get('/discounts/{discount}', [DiscountController::class, 'show']);

Route::get('/discounts/{discount}/products', [ProductController::class, 'getByDiscount']);

// --------------------------------------------------------------------------

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/categories/{category}/products', [ProductController::class, 'getByCategory']);
Route::get('/categories/{category}/brands', [BrandController::class, 'brandByCategory']);

// --------------------------------------------------------------------------

Route::get('/products', [ProductController::class, 'index']);

// --------------------------------------------------------------------------

Route::get('/colors', [ProductColorController::class, 'index']);

// --------------------------------------------------------------------------

Route::get('/tag/{tag}', [TagController::class, 'index']);

// --------------------------------------------------------------------------
