<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',              // Destinatario
        'recipient_name',       // Presso
        'company_name',         // Indirizzo
        'address',              // Numero Civico
        'house_number',         // CAP
        'postal_code',          // CAP
        'country',              // Nazione
        'state',                // Provincia
        'city',                 // Città
        'phone_number',         // Telefono
        'is_primary',           // Indica l'indirizzo principale
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
