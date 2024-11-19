<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'shipping_number' => $this->shipping_number,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order_date' => $this->order_date,
            // Informazioni sull'utente direttamente nel livello principale
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'user_id' => $this->user->id,
            'payment' => $this->payment,
            'data' => $this->data,

            // Prodotti inclusi come array
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                ];
            }),
        ];
    }
}
