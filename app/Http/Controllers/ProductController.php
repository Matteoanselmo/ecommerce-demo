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
            // Converti il range di prezzo in interi (es: moltiplicando per 100)
            $priceRange = [
                intval($request->priceRange[0] * 100),
                intval($request->priceRange[1] * 100),
            ];
            $query->whereBetween('price', $priceRange);
        }

        // Filtra per rating medio utilizzando reviewRatings
        if ($request->filled('ratingStars')) {
            $products = $query->get()->filter(function ($product) use ($request) {
                // Ottieni i valori dei rating
                $ratings = $product->reviewRatings();

                // Calcola la media
                $averageRating = count($ratings) > 0
                    ? array_sum($ratings) / count($ratings) // Somma i valori e dividi per il numero totale
                    : 0; // Nessuna recensione, media 0

                // Confronta la media con ratingStars
                return $averageRating >= floatval($request->ratingStars);
            })->values(); // Resetta le chiavi della Collection
        }



        // Filtra per nome della categoria
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Include relazioni per ottimizzazione
        $query->with(['images', 'category', 'reviews', 'discounts']);

        // Recupera i prodotti filtrati con paginazione
        $products = $query->paginate(10);

        // Ritorna la risposta in formato desiderato
        return ProductResource::collection($products);
    }
}
