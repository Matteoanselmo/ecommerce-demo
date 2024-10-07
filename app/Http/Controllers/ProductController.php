<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller {
    public function getProductsByCategory(Request $request) {
        $category = Category::whereRaw('LOWER(name) = ?', [strtolower($request->category)])->first();

        // Verifica se la categoria esiste
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Recupera i prodotti della categoria con paginazione
        $products = $category->products()->paginate($request->input('per_page', 10));

        // Usa ProductResource per restituire i prodotti con il prezzo scontato
        return ProductResource::collection($products);
    }

    public function show(Product $product) {
        // Carica il prodotto con le immagini correlate
        $product->load('images');

        // Rendi la vista con Inertia, passando i dati del prodotto attraverso ProductResource
        return Inertia::render('ProductDetail', [
            'product' => new ProductResource($product),
        ]);
    }
}
