<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller {
    public function index(Request $request) {
        // Recupera i parametri di query per il filtraggio e l'ordinamento
        $sortField = $request->input('sort_by', 'id'); // Campo di default per ordinamento
        $sortDirection = $request->input('sort_direction', 'asc'); // Direzione di default (ascendente)
        $searchShippingNumber = $request->input('search_shipping_number'); // Parametro di ricerca per numero di spedizione
        $searchOrderNumber = $request->input('search_order_number'); // Parametro di ricerca per numero d'ordine
        $itemsPerPage = $request->input('per_page', 10); // Numero di elementi per pagina (default a 10)

        // Esegui la query con il filtraggio e l'ordinamento per l'utente autenticato
        $orders = Order::with('products')
            ->where('user_id', auth()->id()) // Filtra solo per l'utente loggato
            ->when($searchShippingNumber !== null && $searchShippingNumber !== '', function ($query) use ($searchShippingNumber) {
                // Filtraggio per numero di spedizione
                return $query->where('shipping_number', 'like', "%$searchShippingNumber%");
            })
            ->when($searchOrderNumber !== null && $searchOrderNumber !== '', function ($query) use ($searchOrderNumber) {
                // Filtraggio per numero d'ordine
                return $query->where('order_number', 'like', "%$searchOrderNumber%");
            })
            ->orderBy($sortField, $sortDirection) // Ordinamento basato sui parametri
            ->paginate($itemsPerPage); // Paginazione dinamica basata sul parametro 'per_page'

        // Restituisce i dati paginati
        return response()->json([
            'data' => $orders->items(),
            'total' => $orders->total(),
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
            'per_page' => $orders->perPage(),
        ]);
    }
}
