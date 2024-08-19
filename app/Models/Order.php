<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'order_date',
        'total_amount',
        'user_id',
    ];

    // Relazione con User (un ordine appartiene a un utente)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relazione con Products (un ordine può avere molti prodotti)
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('price_at_order', 'order_quantity');
    }
}
