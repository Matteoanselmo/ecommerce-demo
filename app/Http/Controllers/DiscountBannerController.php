<?php

namespace App\Http\Controllers;

use App\Models\DiscountBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

            // Salva l'immagine su S3 con permessi pubblici
            $imagePath = Storage::disk('s3')->putFileAs('discount_banners', $request->file('image'), $originalName, 'public');

            // Crea un nuovo banner nel database
            $banner = DiscountBanner::create([
                'title' => $originalName,
                'image_path' => Storage::disk('s3')->url($imagePath), // Salva l'URL completo
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
            $banner = DiscountBanner::findOrFail($id);

            // Usa la variabile di ambiente per l'URL base del bucket
            $baseUrl = env('S3_BASE_URL');
            $relativePath = str_replace($baseUrl, '', $banner->image_path);

            if (Storage::disk('s3')->exists($relativePath)) {
                Storage::disk('s3')->delete($relativePath);
            }

            $banner->delete();

            return response()->json([
                'message' => 'Immagine del banner eliminata con successo!',
                'color' => 'success',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Banner non trovato.',
                'color' => 'error',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Errore durante l\'eliminazione dell\'immagine: ' . $e->getMessage(),
                'color' => 'error',
            ], 500);
        }
    }
}
