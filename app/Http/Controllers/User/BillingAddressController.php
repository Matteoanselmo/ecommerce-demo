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
            'type' => 'required|in:private,company', // Tipo di indirizzo
            'address' => 'required|string|max:255',
            'internal' => 'nullable|string|max:50', // Interno
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'first_name' => 'nullable|string|max:100', // Solo per privati
            'last_name' => 'nullable|string|max:100', // Solo per privati
            'tax_code' => 'required|string|max:50', // Codice Fiscale (obbligatorio per entrambi)
            'company_name' => 'nullable|string|max:255', // Solo per aziende
            'vat_number' => 'nullable|string|max:50', // Solo per aziende
            'sdi_code' => 'nullable|string|max:7', // Solo per aziende
            'phone_number' => 'nullable|string|max:20',
            'is_primary' => 'boolean',
        ]);

        $userId = Auth::id();

        // Controlla se l'utente ha già indirizzi di fatturazione
        $existingAddresses = BillingAddress::where('user_id', $userId)->exists();

        // Se non esistono altri indirizzi, imposta is_primary su true
        if (!$existingAddresses) {
            $validatedData['is_primary'] = true;
        } elseif ($validatedData['is_primary'] ?? false) {
            // Se l'indirizzo è impostato come primario, rimuovi il flag dagli altri indirizzi
            BillingAddress::where('user_id', $userId)->update(['is_primary' => false]);
        }

        // Aggiungi user_id ai dati validati
        $validatedData['user_id'] = $userId;

        // Crea il nuovo indirizzo di fatturazione
        $billingAddress = BillingAddress::create($validatedData);

        return response()->json([
            'message' => 'Indirizzo di fatturazione creato con successo!',
            'billing_address' => $billingAddress,
            'color' => 'success',
        ]);
    }


    public function update(Request $request, $id) {
        // Validazione dei dati
        $validated = $request->validate([
            'type' => 'required|in:private,company', // Tipo di indirizzo
            'address' => 'required|string|max:255',
            'internal' => 'nullable|string|max:50', // Interno
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'first_name' => 'nullable|string|max:100', // Solo per privati
            'last_name' => 'nullable|string|max:100', // Solo per privati
            'tax_code' => 'required|string|max:50', // Codice Fiscale (obbligatorio per entrambi)
            'company_name' => 'nullable|string|max:255', // Solo per aziende
            'vat_number' => 'nullable|string|max:50', // Solo per aziende
            'sdi_code' => 'nullable|string|max:7', // Solo per aziende
            'phone_number' => 'nullable|string|max:20',
            'is_primary' => 'boolean',
        ]);

        // Trova l'indirizzo di fatturazione specifico dell'utente autenticato
        $billingAddress = Auth::user()->billingAddresses()->findOrFail($id);

        // Se impostato come primario, aggiorna tutti gli altri indirizzi
        if ($validated['is_primary'] ?? false) {
            Auth::user()->billingAddresses()->update(['is_primary' => false]);
        }

        // Aggiorna l'indirizzo con i dati validati
        $billingAddress->update($validated);

        return response()->json([
            'message' => 'Indirizzo di fatturazione aggiornato con successo!',
            'billing_address' => $billingAddress,
        ]);
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
