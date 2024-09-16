<?php

namespace App\Http\Controllers;

use App\Models\InfoPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfoPolicyController extends Controller {
    public function index() {
        $policies = InfoPolicy::all();
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

            // Salva il file con il suo nome originale
            $filePath = $request->file('file')->storeAs('info_policies', $originalName, 'public');

            // Crea una nuova policy nel database
            $policy = InfoPolicy::create([
                'title' => $originalName,
                'file_path' => $filePath,
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
        $policy = InfoPolicy::findOrFail($id);
        return response()->json($policy);
    }

    public function destroy($id) {
        try {
            // Trova la policy o lancia un'eccezione se non esiste
            $policy = InfoPolicy::findOrFail($id);

            // Verifica se il file esiste prima di tentare di eliminarlo
            if (Storage::disk('public')->exists($policy->file_path)) {
                // Elimina il file PDF dal percorso storage
                Storage::disk('public')->delete($policy->file_path);
            }

            // Elimina la policy dal database
            $policy->delete();

            // Restituisce una risposta JSON di successo
            return response()->json([
                'message' => 'PDF eliminato con successo!',
                'color' => 'success',
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Lascia gestire questa eccezione all'Handler (opzionale)
            throw $e;
        } catch (\Exception $e) {
            // Lascia gestire questa eccezione all'Handler (opzionale)
            throw $e;
        }
    }
}
