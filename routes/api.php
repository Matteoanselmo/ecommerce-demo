<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OverviewController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountBannerController;
use App\Http\Controllers\InfoPolicyController;
use App\Http\Controllers\PricePolicyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReturnPolicyController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\WishlistController;
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
Route::get('/sub-categories', [SubCategoryController::class, 'index']);
Route::get('/get-products', [ProductController::class, 'getProductsByCategory']);

Route::get('/promo-products', [ProductController::class, 'getDiscountedProducts']);

Route::post('/search', [UserSearchController::class, 'search']);
Route::post('/get-subcategories', [SubCategoryController::class, 'getSubCategoryFromCategory']);


Route::get('/search-products', function (Request $request) {
    $searchTerm = $request->input('query'); // Recupera il termine di ricerca dalla query string
    $products = Product::where('name', 'like', '%' . $searchTerm . '%')->get(); // Cerca nei nomi dei prodotti
    foreach ($products as $product) {
        $product->cover_image_url = $product->coverImage();
    }
    return response()->json($products); // Restituisci i risultati in formato JSON
});


Route::get('/discount-banner', [DiscountBannerController::class, 'index']);
Route::get('/info-policies', [InfoPolicyController::class, 'index']);
Route::get('/return-policies', [ReturnPolicyController::class, 'index']);
Route::get('/price-policies', [PricePolicyController::class, 'index']);

Route::post('/user-searches', [UserSearchController::class, 'store']);
Route::get('/user-searches', [UserSearchController::class, 'index']);

// User
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user-orders', [UserOrderController::class, 'index']);
    Route::get('/user-recent-wishlist', [WishlistController::class, 'recent']);
    Route::get('/user-wishlist', [WishlistController::class, 'index']);
});

// Admin
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Panoramica
    Route::get('/dashboard/sales', [OverviewController::class, 'getSalesData']);
    Route::get('/dashboard/traffic', [OverviewController::class, 'getTrafficData']);
    Route::get('/dashboard/disk-space', [OverviewController::class, 'getDiskSpace']);
    Route::get('/dashboard/top-products', [OverviewController::class, 'getTopProductsData']);

    // Personalizzazioni
    Route::post('/discount-banner', [DiscountBannerController::class, 'store']);
    Route::delete('/discount-banner/{id}', [DiscountBannerController::class, 'destroy']);

    Route::post('/info-policies', [InfoPolicyController::class, 'store']);
    Route::delete('/info-policies/{id}', [InfoPolicyController::class, 'destroy']);

    Route::post('/return-policies', [ReturnPolicyController::class, 'store']);
    Route::delete('/return-policies/{id}', [ReturnPolicyController::class, 'destroy']);

    Route::post('/price-policies', [PricePolicyController::class, 'store']);
    Route::delete('/price-policies/{id}', [PricePolicyController::class, 'destroy']);
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
    Route::put('/product/{id}', [AdminProductController::class, 'update']);
    Route::delete('/product/{id}', [AdminProductController::class, 'destroy']);
    Route::delete('/product-images/{imageId}', [AdminProductController::class, 'destroyImage']);

    //Taglie
    Route::get('/size/{categoryId}', [SizeController::class, 'getSizesByCategory']);
    Route::get('/product/{productId}/sizes', [SizeController::class, 'getSizesByProduct']);
    Route::post('/product/{productId}/sizes', [SizeController::class, 'updateProductSizes']);
    Route::post('/product/{productId}/sizes-with-stock', [SizeController::class, 'updateProductSizesWithStock']);

    //Categorie
    Route::post('/product/{productId}/category', [AdminCategoryController::class, 'updateProductCategory']);

    // Faqs
    Route::post('/product/{productId}/faqs', [FaqController::class, 'saveFaqs']);
    Route::delete('/faqs/{faqId}', [FaqController::class, 'deleteFaq']);

    // Certificazioni
    Route::get('/certifications', [CertificationController::class, 'index']);
    Route::get('/certifications/{productId}', [CertificationController::class, 'getProductCertifications']);
    Route::post('/product/{productId}/certifications', [CertificationController::class, 'updateProductCertifications']);
});
