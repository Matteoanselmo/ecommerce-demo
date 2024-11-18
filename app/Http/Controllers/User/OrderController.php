<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller {
    public function index(Request $request) {
        // Recupera il numero di elementi per pagina dai parametri della richiesta, con un valore predefinito di 10
        $perPage = $request->input('per_page', 10);

        // Recupera l'utente loggato e i suoi ordini con i prodotti associati, includendo la paginazione
        $orders = Order::with('products')
            ->where('user_id', auth()->id())
            ->paginate($perPage);

        // Restituisce la lista paginata degli ordini dell'utente loggato
        return response()->json($orders);
    }
}
