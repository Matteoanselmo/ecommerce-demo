<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) {
        // Recupero del prezzo originale
        $originalPrice = $this->price;

        // Calcolo dello sconto applicato
        $discountedPrice = $this->getDiscountedPrice();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'original_price' => intval($originalPrice), // Prezzo originale come intero
            'price' => intval($discountedPrice), // Prezzo scontato come intero
            'category' => $this->category->name ?? null,
            'subcategory' => $this->subCategory->name ?? null,
            'stock_quantity' => $this->stock_quantity,
            'rating_star' => $this->reviewRatings(),
            'cover_image_url' => $this->coverImage(),
            'images' => $this->images,
            'discounts' => $this->discounts,
            'faqs' => $this->faqs,
            'reviews' => $this->userReviews()
        ];
    }
}
