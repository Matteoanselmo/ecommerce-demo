<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $sizes = Size::all();

        return response()->json($sizes);
    }

    public function getSizesByCategory($categoryId) {
        // Trova la categoria con il nome specificato e carica le sue taglie
        $category = Category::with('sizes')->find($categoryId);

        // Controlla se la categoria esiste
        if (!$category) {
            return response()->json(['message' => 'Categoria non trovata'], 404);
        }

        // Ritorna le taglie associate alla categoria
        return response()->json($category->sizes);
    }

    public function getSizesByProduct($productId) {
        // Trova il prodotto con l'ID specificato e carica le sue taglie con il pivot stock
        $product = Product::with(['sizes' => function ($query) {
            $query->select('sizes.id', 'sizes.name');
        }])->find($productId);

        // Verifica se il prodotto esiste
        if (!$product) {
            return response()->json(['message' => 'Prodotto non trovato'], 404);
        }

        // Ritorna le taglie associate al prodotto con lo stock
        $sizesWithStock = $product->sizes->map(function ($size) {
            return [
                'id' => $size->id,
                'name' => $size->name,
                'stock' => $size->pivot->stock,
            ];
        });

        return response()->json($sizesWithStock);
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


    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
