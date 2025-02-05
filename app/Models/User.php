<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relazione con Orders (un utente può avere molti ordini)
    public function orders(): HasMany {
        return $this->hasMany(Order::class);
    }

    // Relazione con ProductReviews (un utente può avere molte recensioni)
    public function reviews(): HasMany {
        return $this->hasMany(ProductReview::class);
    }

    // Relazione con le ricerche dell'utente
    public function searches() {
        return $this->hasMany(UserSearch::class);
    }

    // Relazione con Wishlist (un utente può avere molti prodotti nella lista dei desideri)
    public function wishlists(): HasMany {
        return $this->hasMany(Wishlist::class);
    }
    // Relazione con UserAddress (un utente può avere molti indirizzi)
    public function addresses() {
        return $this->hasMany(UserAddress::class);
    }
    // Relazione con BillingAddress
    public function billingAddresses() {
        return $this->hasMany(BillingAddress::class);
    }
}
