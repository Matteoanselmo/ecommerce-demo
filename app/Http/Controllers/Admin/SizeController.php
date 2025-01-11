<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        // Recupera i parametri di query per il filtraggio e l'ordinamento
        $sortField = $request->input('sort_by', 'id'); // Campo di default per ordinamento
        $sortDirection = $request->input('sort_direction', 'asc'); // Direzione di default (ascendente)
        $searchName = $request->input('search_name', ''); // Parametro di ricerca per nome
        $categoryId = $request->input('category_id', null);  // Parametro di ricerca per categoria

        $itemsPerPage = $request->input('per_page', 10); // Numero di elementi per pagina (default a 10)

        // Esegui la query con il filtraggio e l'ordinamento
        $sizes = Size::with('category', 'products')
            ->when($searchName, function ($query, $searchName) {
                // Filtraggio per nome della taglia
                return $query->where('name', 'like', "%$searchName%");
            })
            ->when(!is_null($categoryId), function ($query) use ($categoryId) {
                // Filtraggio per ID della categoria solo se non è null
                return $query->where('category_id', $categoryId);
            })
            ->orderBy($sortField, $sortDirection) // Ordinamento basato sui parametri
            ->paginate($itemsPerPage); // Paginazione dinamica basata sul parametro 'per_page'

        return response()->json([
            'data' => $sizes->items(),
            'total' => $sizes->total(),
            'current_page' => $sizes->currentPage(),
            'last_page' => $sizes->lastPage(),
            'per_page' => $sizes->perPage(),
        ]);
    }



    public function updateProductSizes(Request $request, $productId) {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'message' => 'Prodotto non trovato',
                'color' => 'danger'
            ], 404);
        }

        // Aggiorna le taglie collegate al prodotto
        $product->sizes()->sync($request->input('size_ids', []));

        return response()->json([
            'message' => 'Taglie aggiornate con successo',
            'color' => 'success'
        ], 200);
    }

    public function updateProductSizesWithStock(Request $request, $productId) {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'message' => 'Prodotto non trovato',
                'color' => 'danger'
            ], 404);
        }

        // Valida l'input per assicurarti che contenga il formato corretto
        $validatedData = $request->validate([
            'sizes' => 'required|array',
            'sizes.*.size_id' => 'required|exists:sizes,id',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        // Prepara i dati per il metodo `sync` con i valori di stock
        $sizesWithStock = collect($validatedData['sizes'])->mapWithKeys(function ($size) {
            return [$size['size_id'] => ['stock' => $size['stock']]];
        })->toArray();

        // Esegui il sync delle taglie con il prodotto e aggiorna lo stock
        $product->sizes()->sync($sizesWithStock);

        return response()->json([
            'message' => 'Taglie e stock aggiornati con successo',
            'color' => 'success'
        ], 200);
    }

    public function store(Request $request) {
        // Valida i dati in arrivo
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Crea una nuova taglia con i dati validati
        $size = Size::create($validatedData);

        return response()->json([
            'message' => 'Taglia creata con successo',
            'color' => 'success',
            'data' => $size,
        ], 201);
    }

    public function update(Request $request, $id) {
        // Trova la taglia con l'ID specificato
        $size = Size::find($id);

        if (!$size) {
            return response()->json([
                'message' => 'Taglia non trovata',
                'color' => 'danger'
            ], 404);
        }

        // Valida il nome in arrivo
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Aggiorna il nome della taglia
        $size->update([
            'name' => $validatedData['name'],
        ]);

        return response()->json([
            'message' => 'Nome della taglia aggiornato con successo',
            'color' => 'success',
            'data' => $size
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        // Trova la taglia con l'ID specificato
        $size = Size::findOrFail($id);

        if (!$size) {
            return response()->json([
                'message' => 'Taglia non trovata',
                'color' => 'danger'
            ], 404);
        }

        // Elimina la taglia
        $size->delete();

        return response()->json([
            'message' => 'Taglia eliminata con successo',
            'color' => 'success'
        ], 200);
    }
}
