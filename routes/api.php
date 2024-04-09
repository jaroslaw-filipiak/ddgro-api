<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccesoriesController;
use App\Http\Controllers\ApplicationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| PRODUCTS
|--------------------------------------------------------------------------
*/

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::get('products/series/{name}', [ProductController::class, 'show_by_series']);
Route::get('products/{id}/edit', [ProductController::class, 'edit']);
Route::put('products/{id}/edit', [ProductController::class, 'update']);
Route::delete('products/{id}/delete', [ProductController::class, 'delete']);
Route::post('product', [ProductController::class, 'store']);


/*
|--------------------------------------------------------------------------
| ACCESORIES
|--------------------------------------------------------------------------
*/

Route::get('accesories', [AccesoriesController::class, 'index']);
Route::get('accesories-for-wood', [AccesoriesController::class, 'get_wood_accesories']);
Route::get('accesories-for-slab', [AccesoriesController::class, 'get_slab_accesories']);

/*
|--------------------------------------------------------------------------
| FORM APPLICATION
|--------------------------------------------------------------------------
*/

Route::get('applications', [ApplicationController::class, 'index']);
Route::post('application', [ApplicationController::class, 'store']);
Route::get('application/{id}', [ApplicationController::class, 'show']);