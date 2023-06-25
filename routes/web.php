<?php

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

use App\Http\Controllers\IndexController;

Route::get('/', [IndexController::class, 'index']);

Route::get('review/gallery', 'App\Http\Controllers\AjaxController@reviewGallery')->name('review.gallery');

Route::get('product/{product_id}.html', 'App\Http\Controllers\ProductController@index')
    ->where('product_id', '[a-z0-9]+'); // 产品ID，只包含小写字母和数字

Route::post('add_to_cart', 'App\Http\Controllers\ProductController@addToCart');

Route::any('{any}', 'App\Http\Controllers\CategoryController@index')->where('any', '.*');


