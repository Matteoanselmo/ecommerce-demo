<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;

class DiscountController extends Controller {
    // Get all discounts
    public function index(Request $request) {
        // Parametri
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');
        $searchName = $request->input('search_name');

        // Query base con eager loading
        $query = Discount::with(['products:id,name', 'categories:id,name']);

        // Filtro per nome
        if ($searchName) {
            $query->where('name', 'like', '%' . $searchName . '%');
        }

        // Ordinamento
        $query->orderBy($sortBy, $sortDirection);

        // Paginazione
        $discounts = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($discounts);
    }


    // Get a specific discount
    public function show($id) {
        $discount = Discount::find($id);

        if (!$discount) {
            return response()->json(['message' => 'Discount not found'], 404);
        }

        return response()->json($discount);
    }

    // Create a new discount
    public function store(Request $request) {
        // Validazione dei dati di input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'discount_value' => 'required|numeric',
            'discount_type' => 'required|string|in:percentage,fixed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'products' => 'array', // Deve essere un array di ID di prodotti
            'products.*' => 'integer|exists:products,id',
            'categories' => 'array', // Deve essere un array di ID di categorie
            'categories.*' => 'integer|exists:categories,id',
        ]);

        // Creazione dello sconto
        $discount = Discount::create($validated);

        // Associazione ai prodotti
        if (!empty($validated['products'])) {
            $discount->products()->attach($validated['products']);
        }

        // Associazione alle categorie
        if (!empty($validated['categories'])) {
            $discount->categories()->attach($validated['categories']);
        }

        return response()->json($discount->load(['products:id,name', 'categories:id,name']), 201);
    }


    // Update a discount
    public function update(Request $request, $id) {
        $discount = Discount::find($id);

        if (!$discount) {
            return response()->json(['message' => 'Discount not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'discount_value' => 'numeric',
            'discount_type' => 'string|in:percentage,fixed',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
        ]);

        $discount->update($validated);

        return response()->json($discount);
    }

    // Delete a discount
    public function destroy($id) {
        $discount = Discount::find($id);

        if (!$discount) {
            return response()->json(['message' => 'Discount not found'], 404);
        }

        $discount->delete();

        return response()->json(['message' => 'Discount deleted successfully']);
    }
}
