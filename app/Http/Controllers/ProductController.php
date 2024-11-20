<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Discount;
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

    public function show($id) {
        // Trova il prodotto con le sue relazioni
        $product = Product::findOrFail($id);

        // Rendi la vista con Inertia, passando i dati del prodotto attraverso ProductResource
        return Inertia::render('ProductDetail', [
            'product' => new ProductResource($product),
        ]);
    }

    public function getDiscountedProducts() {
        // Recupera tutti gli sconti attivi
        $discounts = Discount::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        // Creiamo una struttura di dati per raggruppare i prodotti per sconto
        $groupedProducts = [];

        // Cicla su ciascuno sconto e raggruppa i prodotti associati
        foreach ($discounts as $discount) {
            // Recupera i prodotti associati allo sconto usando la relazione polimorfica
            $products = $discount->morphedByMany(Product::class, 'discountable')->get();

            // Se ci sono prodotti associati allo sconto, li aggiungiamo al gruppo
            if ($products->isNotEmpty()) {
                $groupedProducts[] = [
                    'discount_name' => $discount->name,
                    'products' => ProductResource::collection($products),
                ];
            }
        }
        // Ritorna i prodotti raggruppati per sconto
        return response()->json($groupedProducts);
    }

    public function filterProducts(Request $request) {
        $query = Product::query();

        // Filtra per sotto-categoria
        if ($request->filled('subCategory')) {
            $query->where('subcategory_id', $request->subCategory);
        }

        // Filtra per intervallo di prezzo
        if ($request->filled('priceRange')) {
            $query->whereBetween('price', $request->priceRange);
        }

        // Filtra per rating
        if ($request->filled('rating')) {
            $query->whereHas('reviews', function ($q) use ($request) {
                $q->where('rating_star', '>=', $request->rating);
            });
        }

        // Include relazioni per ottimizzazione
        $query->with(['images', 'category', 'reviews', 'discounts']);

        // Recupera i prodotti filtrati con paginazione
        $products = $query->paginate(10);

        // Modifica gli item per arricchirli con dati aggiuntivi


        // Ritorna la risposta in formato desiderato
        return ProductResource::collection($products);
    }
}
