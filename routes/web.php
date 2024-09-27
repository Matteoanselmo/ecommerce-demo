<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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


Route::get('/prodotti', function (Request $request) {
    $category = $request->input('category', null); // Recupera la categoria dalla query string
    return Inertia::render('ProductsList', ['category' => $category]);
})->name('products.list');

Route::get('/prodotti/{product}', [ProductController::class, 'show'])->name('product.detail');

// ADMIN
Route::prefix('admin/dashboard')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Admin/AdminDashboard');
    })->name('admin.dashboard');

    Route::get('/orders', function () {
        return Inertia::render('Admin/AdminOrders');
    })->name('admin.orders');

    Route::get('/customization', function () {
        return Inertia::render('Admin/Customization');
    })->name('admin.customization');

    Route::get('/user', function () {
        return Inertia::render('Admin/User');
    })->name('admin.user');



    // Route::get('/overview', function () {
    //     return Inertia::render('Admin/DashboardOverview');
    // })->name('admin.dashboard.overview');
    // // Aggiungi qui altre rotte admin
});

// USER
Route::get('/dashboard', function () {
    return Inertia::render('User/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


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
