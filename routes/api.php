<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OverviewController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountBannerController;
use App\Http\Controllers\InfoPolicyController;
use App\Http\Controllers\PricePolicyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReturnPolicyController;
use App\Http\Controllers\SupportTicketController;
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
Route::get('/price-policies', [PricePolicyController::class, 'index']);
Route::get('/discount-banner', [DiscountBannerController::class, 'index']);
Route::get('/info-policy', [InfoPolicyController::class, 'index']);
Route::get('/return-policy', [ReturnPolicyController::class, 'index']);

Route::post('/user-searches', [UserSearchController::class, 'store']);
Route::get('/user-searches', [UserSearchController::class, 'index']);


// Admin
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Panoramica
    Route::get('/dashboard/sales', [OverviewController::class, 'getSalesData']);
    Route::get('/dashboard/traffic', [OverviewController::class, 'getTrafficData']);
    Route::get('/dashboard/disk-space', [OverviewController::class, 'getDiskSpace']);
    Route::get('/dashboard/top-products', [OverviewController::class, 'getTopProductsData']);
    // Personalizzazioni
    Route::post('/price-policies', [PricePolicyController::class, 'store']);
    Route::delete('/price-policies/{id}', [PricePolicyController::class, 'destroy']);
    Route::post('/info-policy', [InfoPolicyController::class, 'store']);
    Route::delete('/info-policy/{id}', [InfoPolicyController::class, 'destroy']);
    Route::post('/return-policy', [ReturnPolicyController::class, 'store']);
    Route::delete('/return-policy/{id}', [ReturnPolicyController::class, 'destroy']);
    // Ordini
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    // User
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::post('/user', [UserController::class, 'store']);
    Route::patch('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);
    // Ticket di Assitenza
    Route::get('/tickets', [SupportTicketController::class, 'index']);
    Route::get('/ticket/{id}', [SupportTicketController::class, 'show']);
    Route::post('/ticket', [SupportTicketController::class, 'store']);
    Route::patch('/ticket/{id}', [SupportTicketController::class, 'updateStatus']);
    Route::delete('/ticket/{id}', [SupportTicketController::class, 'destroy']);

    // Prodotti
    Route::get('/products', [AdminProductController::class, 'index']);
    Route::get('/product/{id}', [AdminProductController::class, 'show']);
    Route::post('/product', [AdminProductController::class, 'store']);
    Route::patch('/product/{id}', [AdminProductController::class, 'update']);
    Route::delete('/product/{id}', [AdminProductController::class, 'destroy']);
});
