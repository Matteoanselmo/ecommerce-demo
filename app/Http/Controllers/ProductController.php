<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller {
    public function getProductsByCategory(Request $request) {

        $category = Category::whereRaw('LOWER(name) = ?', [strtolower($request->category)])->first();

        // Verifica se la categoria esiste
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $products = $category->products()->paginate($request->input('per_page', 10));


        return response()->json($products);
    }
}
