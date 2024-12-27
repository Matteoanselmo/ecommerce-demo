<?php

namespace App\Http\Controllers\Admin;

use App\Models\Datasheet;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DatasheetController extends Controller {

    public function index($productId) {
        $product = Product::findOrFail($productId);
        return $product->datasheets;
    }

    public function store(Request $request, $productId) {
        $product = Product::findOrFail($productId);

        $validated = $request->validate([
            'file.*' => 'required|file|mimes:pdf|max:10240', // Limite di 10MB per ogni file
        ]);

        $uploadedDatasheets = []; // Array per memorizzare i record creati

        foreach ($request->file('file') as $file) {
            // Usa il nome originale del file
            $fileName = $file->getClientOriginalName();

            // Converti il nome del prodotto in un formato URL-friendly
            $productName = str_replace(' ', '_', $product->name);

            // Salva il file su S3 con il nome originale
            $path = \Storage::disk('s3')->putFileAs("datasheets/{$productName}", $file, $fileName, 'public');

            // Crea il record nel database
            $datasheet = $product->datasheets()->create([
                'name' => $fileName, // Salva il nome originale
                'path' => \Storage::disk('s3')->url($path), // URL completo su S3
            ]);

            $uploadedDatasheets[] = $datasheet; // Aggiungi il record creato all'array
        }

        return response()->json([
            'message' => 'Datasheets creati correttamente!',
            'color' => 'success',
        ]); // Restituisci tutti i record creati
    }



    public function show(Datasheet $datasheet) {
        return response()->download(storage_path("app/{$datasheet->path}"));
    }

    public function update(Request $request, Datasheet $datasheet) {
        $validated = $request->validate([
            'file' => 'sometimes|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file')) {
            // Elimina il vecchio file da S3
            \Storage::disk('s3')->delete($datasheet->path);

            // Ottieni il nuovo file
            $file = $request->file('file');

            // Usa il nome originale del file
            $fileName = $file->getClientOriginalName();

            // Salva il nuovo file su S3 con il nome originale
            $path = \Storage::disk('s3')->putFileAs('datasheets', $file, $fileName, 'public');

            // Aggiorna il record nel database
            $datasheet->update([
                'name' => $fileName, // Aggiorna il nome originale
                'path' => \Storage::disk('s3')->url($path), // URL completo su S3
            ]);
        }

        return response()->json($datasheet);
    }


    public function destroy($id) {
        $datasheet = Datasheet::findOrFail($id);

        // Elimina il file da S3
        \Storage::disk('s3')->delete($datasheet->path);

        // Elimina il record dal database
        $datasheet->delete();

        return response()->json([
            'message' => 'Datasheet eliminato correttamente',
            'color' => 'info',
        ]);
    }
}
