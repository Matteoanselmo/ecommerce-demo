<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller {
    public function createPaymentIntent(Request $request) {
        try {

            $description = $request->input('description'); // Accetta la descrizione dal client

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount, // Importo in centesimi (es. €10.00 = 1000)
                'currency' => 'eur',
                'payment_method_types' => ['card'],
                'description' => $description, // Passa la descrizione qui
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function handlePaymentResponse(Request $request) {
        \Log::info($request->all());

        $paymentIntent = $request->input('paymentIntent');
        $products = $request->input('products');
        $userId = auth()->id(); // Supponiamo che l'utente sia autenticato


        // Crea un nuovo ordine
        $order = Order::create([
            'user_id' => $userId,
            'status' => 'confirmed',
            'order_date' => now(),
            'order_number' => $paymentIntent['id'], // Usa l'ID di PaymentIntent come numero ordine
            'total_amount' => $paymentIntent['amount'],
            'payment' => $paymentIntent['payment_method_types'][0], // Es. 'card'
            'details' => $paymentIntent['description'], // Dettagli aggiuntivi dal PaymentIntent
        ]);

        $pivotData = [];
        foreach ($products as $product) {
            $pivotData[$product['id']] = [
                'price_at_order' => $product['price'],
                'order_quantity' => $product['quantity'],
            ];
            $productModel = Product::find($product['id']);
            if ($productModel) {
                $productModel->sizes()->updateExistingPivot($product['sizeId'], [
                    'stock' => \DB::raw('stock - ' . $product['quantity']), // Sottrai la quantità ordinata
                ]);
            }
        }

        $order->products()->sync($pivotData);

        return Inertia::render('Welcome', [
            'message' => $request->input('message'),
            'paymentIntent' => $request->input('paymentIntent'),
        ]);
    }
}
