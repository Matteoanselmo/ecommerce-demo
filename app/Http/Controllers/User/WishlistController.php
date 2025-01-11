<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller {
    public function index() {
        $wishlists = Auth::user()->wishlists()->with('product')->get();

        // Trasforma i prodotti della wishlist usando ProductResource
        $products = $wishlists->map(function ($wishlist) {
            $product = $wishlist->product;
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'discounted_price' => $product->getDiscountedPrice(),
                'cover_image' => $product->coverImage(), // Aggiungi l'immagine di copertina
            ];
        });

        return response()->json($products);
    }

    public function exists($productId) {
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        return response()->json($exists);
    }


    public function store(Request $request) {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'message' => 'Prodotto aggiunto alla Lista di ' . Auth::user()->name . ' 😍',
            'color' => 'success'
        ]);
    }

    public function destroy($id) {
        $wishlist = Wishlist::where('user_id', Auth::id())->where('product_id', $id)->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'message' => 'Prodotto rimosso dalla lista dei desideri 🥹',
                'color' => 'blue-darken-4'
            ]);
        }

        return response()->json([
            'message' => 'Prodotto non trovato nella lista dei desideri 🧐',
            'color' => 'danger'
        ]);
    }

    public function recent() {
        $recentWishlists = Auth::user()
            ->wishlists()
            ->with(['product' => function ($query) {
                $query->select('id', 'name')->with(['images' => function ($q) {
                    $q->select('product_id', 'image_url', 'extension');
                }]);
            }])
            ->latest('created_at')
            ->take(4)
            ->get();

        // Formatta la risposta per includere solo ID e cover image
        $recentProducts = $recentWishlists->map(function ($wishlist) {
            return [
                'id' => $wishlist->product->id,
                'name' => $wishlist->product->name,
                'cover_image_url' => $wishlist->product->coverImage(),
            ];
        });

        return response()->json($recentProducts);
    }
}
