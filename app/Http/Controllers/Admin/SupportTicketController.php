<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupportTicketResource;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller {

    // Mostra tutti i ticket per l'admin
    public function index(Request $request) {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $searchName = $request->input('search_name');
        $searchEmail = $request->input('search_email');
        $searchStatus = $request->input('status'); // Filtro per stato del ticket

        // Query base per ottenere tutti i ticket, escludendo gli admin
        $query = SupportTicket::with('user')->whereHas('user', function ($q) {
            $q->where('role', '!=', 'admin');
        });

        // Filtra per nome utente
        if ($searchName) {
            $query->whereHas('user', function ($q) use ($searchName) {
                $q->where('name', 'like', '%' . $searchName . '%');
            });
        }

        // Filtra per email utente
        if ($searchEmail) {
            $query->whereHas('user', function ($q) use ($searchEmail) {
                $q->where('email', 'like', '%' . $searchEmail . '%');
            });
        }

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
    // Aggiorna lo stato del ticket (solo admin)
    public function updateStatus(Request $request, $id) {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Aperto,In Attesa,Chiuso',
        ]);

        $ticket->update([
            'status' => $request->status,
        ]);

        return response()->json([
            "message" => "Ticket Aggiornato con successo",
            "color" => 'success'
        ]);
    }

    // Dettagli di un singolo ticket
    public function show($id) {
        $ticket = SupportTicket::with('user')->findOrFail($id);
        return response()->json($ticket);
    }
}
