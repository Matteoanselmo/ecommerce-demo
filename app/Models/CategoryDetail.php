<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subcategory_id'
    ];

    // Relazione con SubCategory (un dettaglio di categoria appartiene a una sub-categoria)
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    // Relazione con Products (un dettaglio di categoria può essere associato a molti prodotti)
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
