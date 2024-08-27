<?php

namespace App\Http\Controllers;

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

        $products = $category->products()->paginate($request->input('per_page', 10));

        $products->getCollection()->transform(function ($product) {
            $product->cover_image_url = $product->coverImage();
            $product->rating_star = $product->reviewRatings();
            return $product;
        });

        return response()->json($products);
    }

    public function show(Product $product) {
        // Carica il prodotto con le immagini correlate, ma senza cover_image e senza categoria
        $product->load('images');

        // Aggiunge le valutazioni delle recensioni al prodotto
        $product->rating_star = $product->reviewRatings();

        // Rimuove eventuali informazioni non necessarie
        unset($product->cover_image);
        unset($product->category);

        // Rendi la vista con Inertia, passando i dati del prodotto
        return Inertia::render('ProductDetail', [
            'product' => $product,
        ]);
    }
}
