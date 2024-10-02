<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    public function index(Request $request) {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $searchName = $request->input('search_name');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $categoryId = $request->input('category_id');

        $query = Product::query();

        // Filtro per nome
        if ($searchName) {
            $query->where('name', 'like', '%' . $searchName . '%');
        }

        // Filtro per prezzo minimo e massimo
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        // Filtro per categoria
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Paginazione
        $products = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $products->items(),
            'total' => $products->total(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
        ]);
    }

    public function show($id) {
        // Trova il prodotto con le sue relazioni
        $product = Product::with([
            'subCategory',        // Carica la sub-categoria associata
            'category',           // Carica la categoria associata
            'categoryDetail',     // Carica i dettagli della categoria
            'orders',             // Carica gli ordini associati
            'reviews',            // Carica le recensioni del prodotto
            'discounts',          // Carica eventuali sconti associati
            'images',             // Carica le immagini del prodotto
        ])->findOrFail($id);

        // Restituisci il prodotto con tutte le sue relazioni in formato JSON
        return response()->json($product);
    }
}
