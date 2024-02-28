<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccesoriesController;
use App\Http\Controllers\ApplicationController;
use App\Models\Application;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('products/import', [ProductController::class, 'import']);
Route::get('accesories/import', [AccesoriesController::class, 'import']);


Route::get('/mailable', function () {
    $application = App\Models\Application::find(4);

    return new App\Mail\ApplicationDataMail($application);
});
