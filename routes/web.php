<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;

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


Route::get('/', [IndexController::class, 'index']);

Route::get('review/gallery', [AjaxController::class, 'reviewGallery'])->name('review.gallery');

Route::get('product/{product_id}.html', [ProductController::class, 'index'])
    ->where('product_id', '[a-z0-9]+'); // 产品ID，只包含小写字母和数字

Route::post('add_to_cart', [CartController::class, 'addToCart']);

Route::get('checkout/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('checkout/cart/delete', [CartController::class, 'delete'])->name('cart.delete');
Route::post('checkout/cart/updateItemQty', [CartController::class, 'updateItemQty'])->name('cart.updateItemQty');

Route::any('{any}', [CategoryController::class, 'index'])->where('any', '.*');

