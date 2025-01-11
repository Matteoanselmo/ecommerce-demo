<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',         // Tipo di indirizzo: 'private' o 'company'
        'address',      // Indirizzo
        'internal',     // Interno
        'city',         // Città
        'postal_code',  // CAP
        'state',        // Provincia
        'country',      // Paese
        'first_name',   // Nome (solo per privati)
        'last_name',    // Cognome (solo per privati)
        'tax_code',     // Codice Fiscale (privati e aziende)
        'company_name', // Ragione Sociale (solo per aziende)
        'vat_number',   // Partita IVA (solo per aziende)
        'sdi_code',     // Codice SDI (solo per aziende)
        'phone_number', // Telefono
        'is_primary',   // Indica se è l'indirizzo principale
    ];

    /**
     * Relazione con il modello User.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
