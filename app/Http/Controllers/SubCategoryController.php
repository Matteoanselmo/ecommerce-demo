<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller {
    public function index() {
        $subCategory = SubCategory::all();

        return response()->json($subCategory);
    }

    public function getSubCategoryFromCategory(Request $request) {
        // Validazione del nome della categoria
        $validated = $request->validate([
            'category_name' => 'required|string',
        ]);

        // Trova la categoria ignorando le maiuscole/minuscole
        $category = Category::whereRaw('LOWER(name) = ?', [strtolower($validated['category_name'])])
            ->with('subCategories')
            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category->subCategories);
    }
}
