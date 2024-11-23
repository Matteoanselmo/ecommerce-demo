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
        'brand_id',
        'color_id'
    ];

    public function getDiscountedPrice() {
        $price = $this->price;

        // Controlla gli sconti sul prodotto
        $productDiscount = $this->discounts()
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        // Controlla gli sconti sulla categoria
        $categoryDiscount = $this->category->discounts()
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        // Se esiste uno sconto sul prodotto, applicalo
        if ($productDiscount) {
            $price = $this->applyDiscount($price, $productDiscount);
        }
        // Altrimenti, se esiste uno sconto sulla categoria, applicalo
        elseif ($categoryDiscount) {
            $price = $this->applyDiscount($price, $categoryDiscount);
        }

        return $price;
    }

    private function applyDiscount($price, $discount) {
        if ($discount->discount_type === 'percentage') {
            // Sconto percentuale
            return $price - ($price * ($discount->discount_value / 100));
        } elseif ($discount->discount_type === 'fixed') {
            // Sconto fisso
            return $price - $discount->discount_value;
        }

        return $price; // Ritorna il prezzo senza sconto se nessuno dei due tipi è valido
    }

    // Funzione per ottenere l'immagine di copertina completa (con estensione)
    public function coverImage() {
        $image = $this->images()->first();
        if ($image) {
            return "{$image->image_url}.{$image->extension}";
        }
        return null; // Ritorna null se non ci sono immagini
    }

    public function userReviews() {
        return $this->reviews()
            ->with('user:id,name') // Eager Loading per ottenere solo l'ID e il nome dell'utente
            ->get()
            ->map(function ($review) {
                return [
                    'description' => $review->description,
                    'rating_star' => [$review->rating_star],
                    'user_name' => $review->user ? $review->user->name : null,
                ];
            });
    }

    // Relazione con Faqs
    public function faqs() {
        return $this->hasMany(Faq::class);
    }

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

    // Relazione con Wishlist (un prodotto può essere in molte liste dei desideri)
    public function wishlists(): HasMany {
        return $this->hasMany(Wishlist::class);
    }

    public function sizes() {
        return $this->belongsToMany(Size::class, 'product_sizes')->withPivot('stock');
    }

    public function certifications() {
        return $this->belongsToMany(Certification::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }
}
