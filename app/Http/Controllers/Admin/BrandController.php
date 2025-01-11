<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller {
    public function store(Request $request) {
        try {
            // Valida i dati della richiesta
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:brands,name',
            ]);

            // Crea il nuovo brand
            $brand = Brand::create($validated);

            return response()->json([
                'message' => 'Brand creato correttamente!',
                'brand' => $brand,
                'color' => 'success'
            ], 201);
        } catch (\Throwable $e) {
            // Gestione degli errori imprevisti
            return response()->json([
                'message' => 'An error occurred while creating the brand.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        $brand = Brand::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
        ]);

        // Aggiorna il brand
        $brand->update($validated);

        return response()->json([
            'message' => 'Brand aggiornato correttamente!',
            'brand' => $brand,
            'color' => 'info'
        ], 200);
    }



    public function destroy($id) {
        // Find the brand by ID
        $brand = Brand::findOrFail($id);

        // Delete the brand
        $brand->delete();

        // Return a response (success message)
        return response()->json([
            'message' => 'Brand deleted successfully',
            'color' => 'info'
        ]);
    }
}
