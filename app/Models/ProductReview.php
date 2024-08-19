<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'description',
        'rating_star',
    ];

    // Relazione con User (una recensione appartiene a un utente)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relazione con Product (una recensione appartiene a un prodotto)
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
