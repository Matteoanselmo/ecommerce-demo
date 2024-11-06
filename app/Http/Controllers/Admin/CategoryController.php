<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    public function index(Request $request) {
        // Recupera i parametri di query per il filtraggio e l'ordinamento
        $sortField = $request->input('sort_by', 'id'); // Campo di default per ordinamento
        $sortDirection = $request->input('sort_direction', 'asc'); // Direzione di default (ascendente)
        $searchName = $request->input('search_name', ''); // Parametro di ricerca per nome
        $itemsPerPage = $request->input('per_page', 10); // Numero di elementi per pagina (default a 10)

        // Esegui la query con il filtraggio e l'ordinamento
        $categories = Category::with('subCategories', 'products', 'discounts', 'sizes')
            ->when($searchName, function ($query, $searchName) {
                // Filtraggio per nome categoria
                return $query->where('name', 'like', "%$searchName%");
            })
            ->orderBy($sortField, $sortDirection) // Ordinamento basato sui parametri
            ->paginate($itemsPerPage); // Paginazione dinamica basata sul parametro 'per_page'

        return response()->json([
            'data' => $categories->items(),
            'total' => $categories->total(),
            'current_page' => $categories->currentPage(),
            'last_page' => $categories->lastPage(),
            'per_page' => $categories->perPage(),
        ]);
    }

    public function update(Request $request, $id) {
        // Trova la categoria da aggiornare
        $category = Category::findOrFail($id);

        // Valida i dati in ingresso
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        // Aggiorna i campi della categoria
        $category->update($validatedData);

        // Risposta con la categoria aggiornata e un messaggio di successo
        return response()->json([
            'message' => 'Categoria aggiornata con successo.',
            'data' => $category,
        ]);
    }


    public function updateProductCategory(Request $request, $productId) {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Prodotto non trovato'], 404);
        }

        // Valida la richiesta per assicurarti che il category_id sia presente
        $request->validate([
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($product->category_id = $request->input('category_id')) {
            return response()->json([
                'message' => 'Operazione annullata',
                'color' => 'warning'
            ], 200);
        } else {
            // Elimina tutte le taglie associate al prodotto
            $product->sizes()->detach();

            // Aggiorna la categoria del prodotto
            $product->category_id = $request->input('category_id');
            $product->save();

            return response()->json([
                'message' => 'Categoria aggiornata con successo',
                'color' => 'success'
            ], 200);
        }
    }
}
