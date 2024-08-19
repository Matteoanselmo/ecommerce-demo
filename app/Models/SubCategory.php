<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id'
    ];

    // Relazione con Category (una sub-categoria appartiene a una categoria)
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relazione con CategoryDetails (una sub-categoria può avere molti dettagli)
    public function categoryDetails(): HasMany
    {
        return $this->hasMany(CategoryDetail::class);
    }

    // Relazione con Products (una sub-categoria può avere molti prodotti)
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
