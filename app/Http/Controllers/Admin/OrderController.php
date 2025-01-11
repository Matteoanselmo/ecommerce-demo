<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Notifications\InvoiceUploaded;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

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
        $order = Order::with('products', 'user', 'shippingAddress', 'billingAddress')->findOrFail($id);

        return Inertia::render(
            'Admin/OrderDetails',
            [
                'order' => $order
            ]
        );
    }

    public function update(Request $request, $id) {
        $order = Order::with('user')->findOrFail($id);
        $data = $request->all();

        // Validazione dei dati in arrivo
        $validatedData = $request->validate([
            'status' => ['nullable', Rule::in(['confirmed', 'in_progress', 'on_delivery', 'delivered', 'returned'])],
            'shipping_number' => 'nullable|string|max:255',
            'shipped_in' => 'nullable|date',
        ]);

        // Aggiorna i campi del modello se presenti nella richiesta
        if (isset($validatedData['status'])) {
            $order->status = $validatedData['status'];
        }

        if (isset($validatedData['shipping_number'])) {
            $order->shipping_number = $validatedData['shipping_number'];
        }

        if (isset($validatedData['shipped_in'])) {
            $order->shipped_in = $validatedData['shipped_in'];
        }

        $order->save();

        $user = $order->user;

        if ($user) {
            $user->notify(new OrderStatusUpdated($order));
        }
        return response()->json([
            'message' => 'Ordine aggiornato con successo.',
            'color' => 'info',
            'order' => $order,
        ]);
    }

    public function uploadFattura(Request $request, $id) {
        $order = Order::with('user')->findOrFail($id);

        // Validazione del file fattura
        $validatedData = $request->validate([
            'fattura' => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('fattura')) {
            $userName = str_replace(' ', '_', $order->user->name); // Sostituisci gli spazi con underscore
            $fileName = $request->file('fattura')->getClientOriginalName();
            $filePath = "fatture/{$userName}/{$fileName}";

            // Carica il file su S3 con visibilità pubblica
            $path = $request->file('fattura')->storeAs($filePath, '', [
                'disk' => 's3',
                'visibility' => 'public',
            ]);

            // Salva l'URL della fattura
            $order->fattura = Storage::disk('s3')->url($path);
            $order->save();
        }

        // Recupera l'utente associato all'ordine
        $user = $order->user;

        // Invia la notifica all'utente
        if ($user) {
            $user->notify(new InvoiceUploaded($order));
        }


        return response()->json([
            'message' => 'Fattura caricata con successo.',
            'color' => 'info',
            'fattura_url' => $order->fattura,
        ]);
    }

    public function processReturn(Request $request, $id) {
        $order = Order::findOrFail($id);

        // Logica per gestire il reso
        $order->status = 'returned';
        $order->save();

        return response()->json(['message' => 'Reso processato con successo', 'order' => $order]);
    }
}
