<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller {
    public function index(Request $request) {
        // Recupera i parametri di query per il filtraggio e l'ordinamento
        $sortField = $request->input('sort_by', 'id'); // Campo di default per ordinamento
        $sortDirection = $request->input('sort_direction', 'asc'); // Direzione di default (ascendente)
        $searchName = $request->input('search_name', ''); // Parametro di ricerca per nome
        $searchShippingNumber = $request->input('search_shipping_number', ''); // Parametro di ricerca per numero di spedizione
        $searchOrderNumber = $request->input('search_order_number', ''); // Parametro di ricerca per numero d'ordine
        $itemsPerPage = $request->input('per_page', 10); // Numero di elementi per pagina (default a 10)

        // Esegui la query con il filtraggio e l'ordinamento
        $orders = Order::with('products', 'user')
            ->when($searchName, function ($query, $searchName) {
                // Filtraggio per nome utente
                return $query->whereHas('user', function ($q) use ($searchName) {
                    $q->where('name', 'like', "%$searchName%");
                });
            })
            ->when($searchShippingNumber, function ($query, $searchShippingNumber) {
                // Filtraggio per numero di spedizione
                return $query->orWhere('shipping_number', 'like', "%$searchShippingNumber%");
            })
            ->when($searchOrderNumber, function ($query, $searchOrderNumber) {
                // Filtraggio per numero d'ordine
                return $query->orWhere('order_number', 'like', "%$searchOrderNumber%");
            })
            ->orderBy($sortField, $sortDirection) // Ordinamento basato sui parametri
            ->paginate($itemsPerPage); // Paginazione dinamica basata sul parametro 'per_page'

        return response()->json([
            'data' => OrderResource::collection($orders)->response()->getData(true)['data'],
            'total' => $orders->total(),
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
            'per_page' => $orders->perPage(),
        ]);
    }

    public function show($id) {
        $order = Order::with('products', 'user')->findOrFail($id);

        return response()->json($order);
    }

    public function processReturn(Request $request, $id) {
        $order = Order::findOrFail($id);

        // Logica per gestire il reso
        $order->status = 'returned';
        $order->save();

        return response()->json(['message' => 'Reso processato con successo', 'order' => $order]);
    }
}
