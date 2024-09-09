<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OverviewController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserSearchController;
use App\Models\Product;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Guest
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/get-products', [ProductController::class, 'getProductsByCategory']);

Route::post('/search', [UserSearchController::class, 'search']);

Route::get('/search-products', function (Request $request) {
    $searchTerm = $request->input('query'); // Recupera il termine di ricerca dalla query string
    $products = Product::where('name', 'like', '%' . $searchTerm . '%')->get(); // Cerca nei nomi dei prodotti
    foreach ($products as $product) {
        $product->cover_image_url = $product->coverImage();
    }
    return response()->json($products); // Restituisci i risultati in formato JSON
});

Route::post('/user-searches', [UserSearchController::class, 'store']);
Route::get('/user-searches', [UserSearchController::class, 'index']);
// Admin
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/dashboard/sales', [OverviewController::class, 'getSalesData']);
    Route::get('/dashboard/traffic', [OverviewController::class, 'getTrafficData']);
    Route::get('/dashboard/top-products', [OverviewController::class, 'getTopProductsData']);

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
});
