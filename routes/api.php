<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OverviewController;
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

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/dashboard/sales', [OverviewController::class, 'getSalesData']);
    Route::get('/dashboard/traffic', [OverviewController::class, 'getTrafficData']);
    Route::get('/dashboard/top-products', [OverviewController::class, 'getTopProductsData']);

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
});
