<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller {
    public function index() {
        // Recupera l'utente loggato e i suoi ordini con i prodotti associati
        $orders = Order::with('products')
            ->where('user_id', auth()->id())
            ->get();

        // Restituisce la lista degli ordini dell'utente loggato
        return response()->json($orders);
    }
}
