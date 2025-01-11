<?php

namespace App\Http\Controllers;

use App\Models\PricePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PricePolicyController extends Controller {
    public function index() {
        $policies = PricePolicy::all();
        return response()->json($policies);
    }

    public function store(Request $request) {
        try {
            // Validazione dei dati in ingresso
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:2048',  // Limite di 2 MB per il file PDF
            ]);

            // Ottieni il nome originale del file
            $originalName = $request->file('file')->getClientOriginalName();

            // Salva il file su S3 con accesso pubblico
            $path = Storage::disk('s3')->putFileAs('price_policies', $request->file('file'), $originalName, 'public');

            // Crea una nuova policy nel database con l'URL completo su S3
            PricePolicy::create([
                'title' => $originalName,
                'file_path' => Storage::disk('s3')->url($path),  // URL completo su S3
            ]);

            // Se tutto va bene, restituisci un messaggio di successo
            return response()->json([
                'message' => 'PDF caricato con successo!',
                'color' => 'success'
            ], 201); // Codice HTTP 201 Created

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Restituisce un messaggio di errore specifico per la validazione
            return response()->json([
                'message' => 'Errore di validazione: ' . $e->getMessage(),
                'color' => 'error'
            ], 422); // Codice HTTP 422 Unprocessable Entity

        } catch (\Exception $e) {
            // Restituisce un messaggio generico di errore per altri errori
            return response()->json([
                'message' => 'Errore durante il caricamento del PDF: ' . $e->getMessage(),
                'color' => 'error'
            ], 500); // Codice HTTP 500 Internal Server Error
        }
    }

    public function show($id) {
        $policy = PricePolicy::findOrFail($id);
        return response()->json($policy);
    }

    public function destroy($id) {
        try {
            // Trova la policy o lancia un'eccezione se non esiste
            $policy = PricePolicy::findOrFail($id);

            // Estrai il percorso relativo rimuovendo l'URL di base di S3
            $baseUrl = env('S3_BASE_URL');
            $relativePath = str_replace($baseUrl, '', $policy->file_path);

            // Verifica se il file esiste su S3 prima di tentare di eliminarlo
            if (Storage::disk('s3')->exists($relativePath)) {
                // Elimina il file da S3
                Storage::disk('s3')->delete($relativePath);
            }

            // Elimina la policy dal database
            $policy->delete();

            // Restituisce una risposta JSON di successo
            return response()->json([
                'message' => 'PDF eliminato con successo!',
                'color' => 'success',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Policy non trovata.',
                'color' => 'error',
            ], 404); // Codice HTTP 404 Not Found
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Errore durante l\'eliminazione del PDF: ' . $e->getMessage(),
                'color' => 'error',
            ], 500); // Codice HTTP 500 Internal Server Error
        }
    }
}
