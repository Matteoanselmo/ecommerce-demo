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
        $searchCategory = $request->input('search_category');

        $query = Product::with('category'); // Carica la relazione category

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

        // Filtro per nome della categoria
        if ($searchCategory) {
            $query->whereHas('category', function ($query) use ($searchCategory) {
                $query->where('name', 'like', '%' . $searchCategory . '%');
            });
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

    public function update(Request $request, $id) {

        // Stampa i dati per verificare cosa viene ricevuto
        // logger()->info('Richiesta update prodotto', $request->all());

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // // Aggiorna i dati del prodotto
        $product = Product::findOrFail($id);
        $product->update($validatedData);

        // // Gestione delle immagini
        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $image) {
        //         $path = $image->store('product_images', 'public');
        //         $product->images()->create(['image_url' => $path]);
        //     }
        // }

        $product->setAttribute('rating_star', $product->reviewRatings());

        return response()->json([
            'product' => $product->load([
                'subCategory',        // Carica la sub-categoria associata
                'category',           // Carica la categoria associata
                'categoryDetail',     // Carica i dettagli della categoria
                'orders',             // Carica gli ordini associati
                'reviews',            // Carica le recensioni del prodotto
                'discounts',          // Carica eventuali sconti associati
                'images',             // Carica le immagini del prodotto
                'faqs'                // Carica le faqs del prodotto
            ]),
            'message' => 'Prodotto aggiornato correttamente',
            'color' => 'success'
        ]);
    }
}
