<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model {
    use HasFactory;

    protected $fillable = ['name', 'discount_value', 'discount_type', 'start_date', 'end_date'];

    // Relazione polimorfica per prodotti, categorie e sottocategorie
    public function discountables() {
        return $this->morphToMany(Product::class, 'discountable')
            ->union($this->morphToMany(Category::class, 'discountable'));
    }

    // Relazione polimorfica per prodotti
    public function products() {
        return $this->morphedByMany(Product::class, 'discountable');
    }

    // Relazione polimorfica per categorie
    public function categories() {
        return $this->morphedByMany(Category::class, 'discountable');
    }
}
