<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // // Aggiorna i dati del prodotto
        $product = Product::findOrFail($id);
        $product->update($validatedData);

        // Gestione delle immagini caricate su S3
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Salva il file su S3 con permessi pubblici e ottieni il percorso
                $path = Storage::disk('s3')->put('product_images', $image, 'public');

                // Crea un nuovo record nella tabella 'product_images' con l'URL completo su S3
                $product->images()->create([
                    'image_url' => Storage::disk('s3')->url($path),  // URL completo su S3
                    'extension' => $image->getClientOriginalExtension(),
                ]);
            }
        }


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


    public function destroyImage($id) {
        // Trova l'immagine tramite l'ID
        $image = ProductImage::findOrFail($id);
        $fullUrl = $image->image_url;

        // Ottieni il percorso relativo dall'URL salvato
        $relativePath = str_replace(env('S3_BASE_URL'), '', $fullUrl);

        // Logga il percorso relativo per il debug
        \Log::info("Percorso relativo per l'immagine con ID $id: $relativePath");

        // Verifica e cancella l'immagine da S3
        if (Storage::disk('s3')->exists($relativePath)) {
            Storage::disk('s3')->delete($relativePath);
        }

        // // Elimina il record dal database
        $image->delete();

        return response()->json([
            'message' => 'Immagine eliminata correttamente',
            'color' => 'success'
        ]);
    }
}
