<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DatasheetController extends Controller {
    public function index($productId) {
        $product = Product::findOrFail($productId);
        return response()->json($product->datasheet);
    }
}
