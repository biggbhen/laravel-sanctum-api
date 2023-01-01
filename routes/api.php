<?php

use App\Http\Controllers\ProductsController;
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

// public routes
Route::resource('products', ProductsController::class);
// Route::get('/products/search/{name}', [ProductsController::class, 'search']);


// protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/products/search/{name}', [ProductsController::class, 'search']);
});
