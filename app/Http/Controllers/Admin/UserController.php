<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    /**
     * Mostra un elenco di utenti con ruolo diverso da 'admin'.
     */
    public function index(Request $request) {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $searchName = $request->input('search_name');
        $searchEmail = $request->input('search_email');

        $query = User::where('role', '!=', 'admin');

        if ($searchName) {
            $query->where('name', 'like', '%' . $searchName . '%');
        }

        if ($searchEmail) {
            $query->where('email', 'like', '%' . $searchEmail . '%');
        }

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $users->items(),
            'total' => $users->total(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
        ]);
    }

    /**
     * Mostra i dettagli di un singolo utente.
     */
    public function show($id) {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Crea un nuovo utente.
     */
    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string'
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role']
        ]);

        return response()->json($user, 201);
    }

    /**
     * Aggiorna i dati di un utente esistente.
     */
    public function update(Request $request, $id) {
        // Trova l'utente da aggiornare
        $user = User::findOrFail($id);

        // Validazione dei campi, la password è facoltativa (nullable)
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',  // La password non è obbligatoria
            'role' => 'string'
        ]);

        // Aggiorna i campi solo se presenti nella richiesta
        $user->name = $validatedData['name'] ?? $user->name;
        $user->email = $validatedData['email'] ?? $user->email;

        // Se la password è stata inviata, aggiorna solo allora
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        // Aggiorna il ruolo (se presente)
        $user->role = $validatedData['role'] ?? $user->role;

        // Salva le modifiche
        $user->save();

        // Restituisci una risposta con i dati aggiornati
        return response()->json($user, 200);
    }


    /**
     * Elimina un utente.
     */
    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}
