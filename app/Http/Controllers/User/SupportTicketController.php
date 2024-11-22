<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SupportTicketResource;

class SupportTicketController extends Controller {

    public function index(Request $request) {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $searchStatus = $request->input('status'); // Filtro per stato del ticket

        // Query base per ottenere i ticket dell'utente autenticato
        $query = SupportTicket::with('user')->where('user_id', auth()->id());

        // Filtra per stato del ticket
        if ($searchStatus) {
            $query->where('status', $searchStatus);
        }

        // Paginazione
        $tickets = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => SupportTicketResource::collection($tickets)->response()->getData(true)['data'],
            'total' => $tickets->total(),
            'current_page' => $tickets->currentPage(),
            'last_page' => $tickets->lastPage(),
            'per_page' => $tickets->perPage(),
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'product' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'product' => $validated['product'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
        ]);

        // Notifica push all'admin
        // Puoi usare Laravel Notifications o Broadcast per la notifica

        return response()->json($ticket, 201);
    }

    public function destroy($id) {
        // Recupera il ticket verificando che appartenga all'utente autenticato
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        // Se il ticket non esiste o non appartiene all'utente, restituisci un errore
        if (!$ticket) {
            return response()->json([
                'message' => 'Ticket non trovato o non autorizzato.',
            ], 403);
        }

        // Elimina il ticket
        $ticket->delete();

        // Risposta di successo
        return response()->json([
            'message' => 'Ticket eliminato con successo.',
        ], 200);
    }
}
