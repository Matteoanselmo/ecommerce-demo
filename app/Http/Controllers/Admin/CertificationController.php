<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Product;
use Illuminate\Http\Request;

class CertificationController extends Controller {
    // Recupera tutte le certificazioni
    public function index() {
        $certifications = Certification::all();
        return response()->json($certifications);
    }

    // Recupera le certificazioni di un prodotto specifico
    public function getProductCertifications($productId) {
        $product = Product::with('certifications')->findOrFail($productId);
        return response()->json($product->certifications);
    }

    public function updateProductCertifications(Request $request, $productId) {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'message' => 'Prodotto non trovato',
                'color' => 'danger'
            ], 404);
        }

        // Aggiorna le certificazioni collegate al prodotto
        $product->certifications()->sync($request->input('certification_ids', []));

        return response()->json([
            'message' => 'Certificazioni aggiornate con successo',
            'color' => 'success'
        ], 200);
    }
}
