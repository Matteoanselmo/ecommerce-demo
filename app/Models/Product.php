<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'subcategory_id',
        'category_id',
        'price',
        'categorydetails_id',
        'stock_quantity',
    ];

    // Relazione con SubCategory (un prodotto appartiene a una sub-categoria)
    public function subCategory(): BelongsTo {
        return $this->belongsTo(SubCategory::class);
    }

    // Relazione con Category (un prodotto appartiene a una categoria)
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    // Relazione con CategoryDetail (un prodotto può avere un dettaglio di categoria opzionale)
    public function categoryDetail(): BelongsTo {
        return $this->belongsTo(CategoryDetail::class);
    }

    // Relazione con Orders (un prodotto può appartenere a molti ordini)
    public function orders(): BelongsToMany {
        return $this->belongsToMany(Order::class, 'order_product')->withPivot('price_at_order', 'order_quantity');
    }

    // Relazione con ProductReviews (un prodotto può avere molte recensioni)
    public function reviews(): HasMany {
        return $this->hasMany(ProductReview::class);
    }

    // Relazione polimorfica con gli sconti
    public function discounts(): MorphToMany {
        return $this->morphToMany(Discount::class, 'discountable');
    }

    // Relazione con ProductImage
    public function images(): HasMany {
        return $this->hasMany(ProductImage::class);
    }

    public function reviewRatings() {
        return $this->reviews()->pluck('rating_star');
    }

    // Funzione per ottenere l'immagine di copertina completa (con estensione)
    public function coverImage() {
        $image = $this->images()->first();
        if ($image) {
            return "{$image->image_url}.{$image->extension}";
        }
        return null; // Ritorna null se non ci sono immagini
    }
}
