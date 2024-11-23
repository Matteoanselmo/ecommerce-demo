<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller {
    /**
     * Crea un nuovo colore.
     */
    public function store(Request $request) {
        try {
            // Valida i dati della richiesta
            $validated = $request->validate([
                'name' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/|unique:colors,name',
            ]);

            // Crea il nuovo colore
            $color = Color::create($validated);

            return response()->json([
                'message' => 'Colore creato correttamente!',
                'colorData' => $color,
                'color' => 'success'
            ], 201);
        } catch (\Throwable $e) {
            // Gestione degli errori imprevisti
            return response()->json([
                'message' => 'Si è verificato un errore durante la creazione del colore.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aggiorna un colore esistente.
     */
    public function update(Request $request, $id) {
        $color = Color::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/|unique:colors,name,' . $color->id,
        ]);

        // Aggiorna il colore
        $color->update($validated);

        return response()->json([
            'message' => 'Colore aggiornato correttamente!',
            'colorData' => $color,
            'color' => 'info'
        ], 200);
    }

    /**
     * Elimina un colore.
     */
    public function destroy($id) {
        // Trova il colore tramite ID
        $color = Color::findOrFail($id);

        // Elimina il colore
        $color->delete();

        return response()->json([
            'message' => 'Colore eliminato correttamente',
            'color' => 'success'
        ]);
    }
}
