<?php

namespace App\Http\Controllers;

use App\Models\DiscountBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiscountBannerController extends Controller {
    public function index() {
        $banners = DiscountBanner::all();
        return response()->json($banners);
    }

    public function store(Request $request) {
        try {
            // Validazione dei dati in ingresso
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',  // Limite di 2 MB per l'immagine
            ]);

            // Ottieni il nome originale dell'immagine
            $originalName = $request->file('image')->getClientOriginalName();

            // Salva l'immagine con il suo nome originale
            $imagePath = $request->file('image')->storeAs('discount_banners', $originalName, 'public');

            // Crea un nuovo banner nel database
            $banner = DiscountBanner::create([
                'title' => $originalName,
                'image_path' => $imagePath,
            ]);

            // Se tutto va bene, restituisci un messaggio di successo
            return response()->json([
                'message' => 'Immagine del banner caricata con successo!',
                'color' => 'success',
            ], 201); // Codice HTTP 201 Created

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Restituisce un messaggio di errore specifico per la validazione
            return response()->json([
                'message' => 'Errore di validazione: ' . $e->getMessage(),
                'color' => 'error',
            ], 422); // Codice HTTP 422 Unprocessable Entity

        } catch (\Exception $e) {
            // Restituisce un messaggio generico di errore per altri errori
            return response()->json([
                'message' => 'Errore durante il caricamento dell\'immagine: ' . $e->getMessage(),
                'color' => 'error',
            ], 500); // Codice HTTP 500 Internal Server Error
        }
    }

    public function show($id) {
        $banner = DiscountBanner::findOrFail($id);
        return response()->json($banner);
    }

    public function destroy($id) {
        try {
            // Trova il banner o lancia un'eccezione se non esiste
            $banner = DiscountBanner::findOrFail($id);

            // Verifica se il file esiste prima di tentare di eliminarlo
            if (Storage::disk('public')->exists($banner->image_path)) {
                // Elimina l'immagine dal percorso storage
                Storage::disk('public')->delete($banner->image_path);
            }

            // Elimina il banner dal database
            $banner->delete();

            // Restituisce una risposta JSON di successo
            return response()->json([
                'message' => 'Immagine del banner eliminata con successo!',
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
