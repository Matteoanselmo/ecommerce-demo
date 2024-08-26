<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
