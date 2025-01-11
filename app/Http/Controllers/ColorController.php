<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller {
    public function index() {
        // Recupera tutti i brand dal database
        $colors = Color::all();

        // Restituisce i dati in formato JSON
        return response()->json($colors);
    }
}
