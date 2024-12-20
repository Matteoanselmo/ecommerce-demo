<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',        // Nome privato o denominazione azienda
        'tax_id',      // P.IVA o Codice Fiscale
        'address',
        'house_number',
        'postal_code',
        'city',
        'state',
        'country',
        'phone_number',
        'is_primary',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
