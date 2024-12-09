<?php

use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\ColorController as AdminColorController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OverviewController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SizeController as AdminSizeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountBannerController;
use App\Http\Controllers\InfoPolicyController;
use App\Http\Controllers\PricePolicyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReturnPolicyController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\SupportTicketController as UserSupportTicketController;
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

// Guest

// Categorie
Route::get('/all-categories', [CategoryController::class, 'index']);
Route::get('/sub-categories', [SubCategoryController::class, 'index']);
// Prodotti
Route::get('/get-products', [ProductController::class, 'getProductsByCategory']);
Route::post('/filter-products', [ProductController::class, 'filterProducts']);
Route::get('/promo-products', [ProductController::class, 'getDiscountedProducts']);
Route::get('/search-products', function (Request $request) {
    $searchTerm = $request->input('query'); // Recupera il termine di ricerca dalla query string
    $products = Product::where('name', 'like', '%' . $searchTerm . '%')->get(); // Cerca nei nomi dei prodotti
    foreach ($products as $product) {
        $product->cover_image_url = $product->coverImage();
    }
    return response()->json($products); // Restituisci i risultati in formato JSON
});

Route::post('/search', [UserSearchController::class, 'search']);
// Sotto Categorie
Route::post('/get-subcategories', [SubCategoryController::class, 'getSubCategoryFromCategory']);
Route::post('/get-subcategories-by-id', [SubCategoryController::class, 'getSubCategoriesByCategoryId']);

// Taglie
Route::get('/size/{categoryId}', [SizeController::class, 'getSizesByCategory']);
Route::get('/product/{productId}/sizes', [SizeController::class, 'getSizesByProduct']);

// Allegati
Route::get('/discount-banner', [DiscountBannerController::class, 'index']);
Route::get('/info-policies', [InfoPolicyController::class, 'index']);
Route::get('/return-policies', [ReturnPolicyController::class, 'index']);
Route::get('/price-policies', [PricePolicyController::class, 'index']);
// Ricerche
Route::post('/user-searches', [UserSearchController::class, 'store']);
Route::get('/user-searches', [UserSearchController::class, 'index']);

// Brands
Route::get('/all-brands', [BrandController::class, 'index']);
// Color
Route::get('/all-colors', [ColorController::class, 'index']);
// Pagamento
Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
// User
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user-orders', [UserOrderController::class, 'index']);

    // WishList
    Route::get('/user-recent-wishlist', [WishlistController::class, 'recent']);
    Route::get('/exist/wishlist/{productId}', [WishlistController::class, 'exists']);
    Route::get('/user-wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy']);

    // Addresses
    Route::get('/user-addresses', [AddressController::class, 'index']);
    Route::post('/user-addresses', [AddressController::class, 'store']);
    Route::put('/user-addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/user-addresses/{id}', [AddressController::class, 'destroy']);

    // Ticket
    Route::get('/support-tickets', [UserSupportTicketController::class, 'index']);
    Route::post('/support-tickets', [UserSupportTicketController::class, 'store']);
    Route::put('/support-tickets/{id}', [UserSupportTicketController::class, 'update']);
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
    Route::get('/sizes', [AdminSizeController::class, 'index']);
    Route::post('/product/{productId}/sizes', [AdminSizeController::class, 'updateProductSizes']);
    Route::post('/product/{productId}/sizes-with-stock', [AdminSizeController::class, 'updateProductSizesWithStock']);
    Route::post('/size', [AdminSizeController::class, 'store']);
    Route::patch('/size/{sizeId}', [AdminSizeController::class, 'update']);
    Route::delete('/size/{sizeId}', [AdminSizeController::class, 'destroy']);

    //Categorie
    Route::post('/product/{productId}/category', [AdminCategoryController::class, 'updateProductCategory']);
    Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::post('/category', [AdminCategoryController::class, 'store']);
    Route::patch('/category/{id}', [AdminCategoryController::class, 'update']);
    Route::delete('/category/{id}', [AdminCategoryController::class, 'destroy']);

    // Faqs
    Route::post('/product/{productId}/faqs', [FaqController::class, 'saveFaqs']);
    Route::delete('/faqs/{faqId}', [FaqController::class, 'deleteFaq']);

    // Certificazioni
    Route::get('/certifications', [CertificationController::class, 'index']);
    Route::get('/certifications/{productId}', [CertificationController::class, 'getProductCertifications']);
    Route::post('/product/{productId}/certifications', [CertificationController::class, 'updateProductCertifications']);

    // Brand
    Route::prefix('/brands')->group(function () {
        Route::post('/', [AdminBrandController::class, 'store']); // Creazione di un nuovo brand
        Route::patch('/{id}', [AdminBrandController::class, 'update']); // Aggiornamento di un brand esistente
        Route::delete('/{id}', [AdminBrandController::class, 'destroy']); // Eliminazione di un brand
    });
    // Color
    Route::prefix('/colors')->group(function () {
        Route::post('/', [AdminColorController::class, 'store']); // Creazione di un nuovo brand
        Route::patch('/{id}', [AdminColorController::class, 'update']); // Aggiornamento di un brand esistente
        Route::delete('/{id}', [AdminColorController::class, 'destroy']); // Eliminazione di un brand
    });
});
