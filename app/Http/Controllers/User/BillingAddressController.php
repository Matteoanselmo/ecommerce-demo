<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingAddressController extends Controller {
    public function index() {
        $user_billing_addresses = Auth::user()->billingAddresses;
        return response()->json($user_billing_addresses);
    }

    public function store(Request $request) {
        // Validazione dei dati
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255', // Nome privato o denominazione azienda
            'tax_id' => 'nullable|string|max:50', // Partita IVA o Codice Fiscale
            'address' => 'required|string|max:255', // Indirizzo
            'house_number' => 'nullable|string|max:20', // Numero civico
            'postal_code' => 'required|string|max:10', // CAP
            'city' => 'required|string|max:100', // Città
            'state' => 'required|string|max:100', // Provincia
            'country' => 'required|string|max:100', // Paese
            'phone_number' => 'nullable|string|max:20', // Telefono
            'is_primary' => 'boolean', // Indirizzo principale
        ]);

        // Se l'indirizzo è primario, rimuovi il flag dagli altri indirizzi dell'utente
        if ($validatedData['is_primary'] ?? false) {
            BillingAddress::where('user_id', $validatedData['user_id'])
                ->update(['is_primary' => false]);
        }

        // Crea il nuovo indirizzo di fatturazione
        BillingAddress::create($validatedData);

        return response()->json([
            'message' => 'Indirizzo di fatturazione creato con successo!',
            'color' => 'success'
        ]);
    }

    public function update(Request $request, $id) {
        // Validazione dei dati
        $validated = $request->validate([
            'name' => 'required|string|max:255', // Nome privato o denominazione azienda
            'tax_id' => 'nullable|string|max:50', // Partita IVA o Codice Fiscale
            'address' => 'required|string|max:255', // Indirizzo
            'house_number' => 'nullable|string|max:20', // Numero civico
            'postal_code' => 'required|string|max:10', // CAP
            'city' => 'required|string|max:100', // Città
            'state' => 'required|string|max:100', // Provincia
            'country' => 'required|string|max:100', // Paese
            'phone_number' => 'nullable|string|max:20', // Telefono
            'is_primary' => 'boolean', // Indirizzo principale
        ]);

        // Trova l'indirizzo di fatturazione specifico dell'utente autenticato
        $billingAddress = Auth::user()->billingAddresses()->findOrFail($id);

        // Se impostato come primario, aggiorna tutti gli altri indirizzi
        if ($validated['is_primary'] ?? false) {
            Auth::user()->billingAddresses()->update(['is_primary' => false]);
        }

        // Aggiorna l'indirizzo con i dati validati
        $billingAddress->update($validated);

        return response()->json($billingAddress);
    }

    public function destroy($id) {
        // Trova l'indirizzo di fatturazione specifico dell'utente autenticato
        $billingAddress = Auth::user()->billingAddresses()->findOrFail($id);

        // Elimina l'indirizzo
        $billingAddress->delete();

        return response()->json([
            'message' => 'Indirizzo di fatturazione eliminato con successo.',
        ]);
    }
}
