<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSearchController extends Controller {
    public function store(Request $request) {
        if (Auth::check()) {
            $request->validate([
                'search_query' => 'required|string|max:255',
            ]);

            UserSearch::create([
                'user_id' => Auth::id(),
                'search_query' => $request->search_query,
            ]);

            return response()->json(['message' => 'Search saved successfully'], 201);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function index() {
        if (Auth::check()) {
            $searches = Auth::user()->searches()->orderBy('created_at', 'desc')->get();

            return response()->json($searches);
        }

        return response()->json([]);
    }

    public function search(Request $request) {
        $query = $request->input('query');
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        // Search for categories and subcategories matching the query
        $categories = Category::where('name', 'like', '%' . $query . '%')->pluck('id');
        $subCategories = SubCategory::where('name', 'like', '%' . $query . '%')->pluck('id');

        // Filter products based on name, categories, and subcategories, and paginate the results
        $products = Product::where('name', 'like', '%' . $query . '%')
            ->orWhereIn('category_id', $categories)
            ->orWhereIn('subcategory_id', $subCategories)
            ->paginate($perPage);

        // Determine the selected category based on the products
        $selectedCategory = $products->isNotEmpty() ? $products->first()->category : null;

        // Transform products to add additional information
        $products->getCollection()->transform(function ($product) {
            $product->cover_image_url = $product->coverImage();
            $product->rating_star = $product->reviewRatings();
            return $product;
        });

        return response()->json([
            'products' => $products,
            'selected_category' => $selectedCategory, // Return the selected category
        ]);
    }

    // Nuova funzione per filtrare i prodotti e redirezionare con Inertia
    public function filterProducts(Request $request) {
        $query = $request->input('search_query');

        $products = Product::where('name', 'like', '%' . $query . '%')->get();

        return response()->json($products);
    }
}
