<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth', 'web']], function() {
    // uses 'auth' middleware plus all middleware from $middlewareGroups['web']
    Route::resource('products',ProductController::class);
    Route::get('get-products', [ProductController::class, 'getProducts'])->name('get-products');
    Route::delete('deleteimage/{id}', [ProductController::class,'deleteImage']);
});




