<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/carello', function () {
    return Inertia::render('CartPage');
})->name('cart');

Route::get('/checkout', function () {
    return Inertia::render('CheckOut');
})->name('checkout');


Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

Route::get('/prodotti', function (Request $request) {
    $category = $request->input('category', null); // Recupera la categoria dalla query string
    return Inertia::render('ProductsList', ['category' => $category]);
})->name('products.list');

Route::get('/prodotti/{id}', [ProductController::class, 'show'])->name('product.detail');

// ADMIN
Route::prefix('admin/dashboard')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Admin/AdminDashboard');
    })->name('admin.dashboard');

    Route::get('/orders', function () {
        return Inertia::render('Admin/AdminOrders');
    })->name('admin.orders');

    Route::get('/order-details/{id}', [OrderController::class, 'show'])->name('order.details');

    Route::get('/customization', function () {
        return Inertia::render('Admin/Customization');
    })->name('admin.customization');

    Route::get('/user', function () {
        return Inertia::render('Admin/User');
    })->name('admin.user');

    Route::get('/tickets', function () {
        return Inertia::render('Admin/SupportTickets');
    })->name('admin.tickets');

    Route::get('/products', function () {
        return Inertia::render('Admin/Products');
    })->name('admin.products');

    Route::get('/categories', function () {
        return Inertia::render('Admin/Categories');
    })->name('admin.categories');

    Route::get('/sizes', function () {
        return Inertia::render('Admin/Sizes');
    })->name('admin.sizes');

    Route::get('/products/{product}', function ($productId) {
        $product = Product::with([
            'subCategory',        // Carica la sub-categoria associata
            'category',           // Carica la categoria associata
            'categoryDetail',     // Carica i dettagli della categoria
            'orders',             // Carica gli ordini associati
            'reviews',            // Carica le recensioni del prodotto
            'discounts',          // Carica eventuali sconti associati
            'images',             // Carica le immagini del prodotto
            'faqs'                // Carica le faqs del prodotto
        ])->findOrFail($productId);
        $product->rating_star = $product->reviewRatings();
        return Inertia::render('Admin/ProductCrud', [
            'product' => $product
        ]);
    })->name('admin.product.crud');



    // Route::get('/overview', function () {
    //     return Inertia::render('Admin/DashboardOverview');
    // })->name('admin.dashboard.overview');
    // // Aggiungi qui altre rotte admin
});

// USER
Route::prefix('/dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('User/Dashboard');
    })->name('user.dashboard');

    Route::get('/wish-list', function () {
        return Inertia::render('User/WishList');
    })->name('user.wishlist');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::fallback(function () {
    return Inertia::render('ErrorPage', [
        'statusCode' => 404,
        'message' => 'Page not found'
    ]);
});

require __DIR__ . '/auth.php';
