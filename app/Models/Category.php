<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Category extends Model {
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    // Relazione con SubCategory (una categoria può avere molte sub-categorie)
    public function subCategories(): HasMany {
        return $this->hasMany(SubCategory::class);
    }

    // Relazione con Products (una categoria può avere molti prodotti)
    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }

    // Relazione polimorfica con gli sconti
    public function discounts(): MorphToMany {
        return $this->morphToMany(Discount::class, 'discountable');
    }
}
