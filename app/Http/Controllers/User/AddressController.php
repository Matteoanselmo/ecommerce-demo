<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAddress;

class AddressController extends Controller {
    /**
     * Mostra tutti gli indirizzi dell'utente autenticato
     */
    public function index() {
        $user_addresses = Auth::user()->addresses;
        return response()->json($user_addresses);
    }

    /**
     * Aggiunge un nuovo indirizzo per l'utente autenticato
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'house_number' => 'required|string|max:10',
            'postal_code' => 'required|string|max:10',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'is_primary' => 'boolean',
        ]);

        $user = Auth::user();

        // Controlla se l'utente ha già indirizzi
        $hasAddresses = $user->addresses()->exists();

        // Se non esistono indirizzi, imposta is_primary su true
        if (!$hasAddresses) {
            $validated['is_primary'] = true;
        } elseif ($validated['is_primary'] ?? false) {
            // Se impostato come primario, aggiorna gli altri indirizzi per rimuovere il flag
            $user->addresses()->update(['is_primary' => false]);
        }

        // Crea il nuovo indirizzo
        $user->addresses()->create($validated);

        return response()->json([
            'message' => 'Indirizzo aggiunto correttamente!',
            'color' => 'success',
        ]);
    }


    /**
     * Aggiorna un indirizzo esistente dell'utente autenticato
     */
    public function update(Request $request, $id) {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'house_number' => 'required|string|max:10',
            'postal_code' => 'required|string|max:10',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'is_primary' => 'boolean',
        ]);

        $address = Auth::user()->addresses()->findOrFail($id);

        // Se impostato come primario, aggiorna tutti gli altri indirizzi
        if ($validated['is_primary'] ?? false) {
            Auth::user()->addresses()->update(['is_primary' => false]);
        }

        $address->update($validated);

        return response()->json($address);
    }

    /**
     * Elimina un indirizzo dell'utente autenticato
     */
    public function destroy($id) {
        $address = Auth::user()->addresses()->findOrFail($id);

        $address->delete();

        return response()->json(['message' => 'Indirizzo eliminato con successo.']);
    }
}
