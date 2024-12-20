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

    public function store(Request $request) {
        try {
            // Valida i dati della richiesta
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:brands,name',
            ]);

            // Crea il nuovo colore
            $certification = Certification::create($validated);

            return response()->json([
                'message' => 'Certtificazione creata correttamente!',
                'colorData' => $certification,
                'color' => 'success'
            ], 201);
        } catch (\Throwable $e) {
            // Gestione degli errori imprevisti
            return response()->json([
                'message' => 'Si è verificato un errore durante la creazione della certificazione.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        $certification = Certification::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $certification->id,
        ]);

        // Aggiorna il colore
        $certification->update($validated);

        return response()->json([
            'message' => 'Certificazione aggiornata correttamente!',
            'colorData' => $certification,
            'color' => 'info'
        ], 200);
    }

    public function destroy($id) {
        // Trova il colore tramite ID
        $certification = Certification::findOrFail($id);

        // Elimina il colore
        $certification->delete();

        return response()->json([
            'message' => 'Certificazione eliminata correttamente',
            'color' => 'info'
        ]);
    }
}
