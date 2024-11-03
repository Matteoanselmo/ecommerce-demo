<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    public function updateProductCategory(Request $request, $productId) {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Prodotto non trovato'], 404);
        }

        // Valida la richiesta per assicurarti che il category_id sia presente
        $request->validate([
            'category_id' => 'required|exists:categories,id'
        ]);

        // Elimina tutte le taglie associate al prodotto
        $product->sizes()->detach();

        // Aggiorna la categoria del prodotto
        $product->category_id = $request->input('category_id');
        $product->save();

        return response()->json(['message' => 'Categoria aggiornata con successo'], 200);
    }
}
