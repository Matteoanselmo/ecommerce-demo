<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product',
        'subject',
        'description',
        'status',
    ];

    /**
     * Relazione con l'utente
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
