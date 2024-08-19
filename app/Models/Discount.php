<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model {
    use HasFactory;

    protected $fillable = ['name', 'discount_value', 'discount_type', 'start_date', 'end_date'];

    // Relazione polimorfica per prodotti, categorie e sottocategorie
    public function discountables(): MorphToMany {
        return $this->morphedByMany(Product::class, 'discountable')
            ->morphedByMany(Category::class, 'discountable')
            ->morphedByMany(SubCategory::class, 'discountable');
    }
}
