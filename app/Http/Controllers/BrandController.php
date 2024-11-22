<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller {
    public function index() {
        // Recupera tutti i brand dal database
        $brands = Brand::all();

        // Restituisce i dati in formato JSON
        return response()->json($brands);
    }
}
