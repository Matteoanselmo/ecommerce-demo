<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSearch extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'search_query',
    ];

    // Relazione con l'utente
    public function user() {
        return $this->belongsTo(User::class);
    }
}
