<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller {
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
}
