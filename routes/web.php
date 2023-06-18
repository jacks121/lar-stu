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

use App\Models\Advertisement;

Route::get('/advertisement/{code}', function ($code) {
    $advertisement = new Advertisement();
    $result = $advertisement->getAdvertisementByCode($code);
    
    // 处理获取到的结果
    // ...

    return response()->json($result);
});

use App\Http\Controllers\IndexController;

Route::get('/', [IndexController::class, 'index']);

Route::any('{any}', 'App\Http\Controllers\CategoryController@index')->where('any', '.*');

