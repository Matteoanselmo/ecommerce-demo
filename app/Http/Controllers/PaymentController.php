<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        return Inertia::render('Welcome', [
            'status' => $request->input('status'),
            'message' => $request->input('message'),
            'paymentIntent' => $request->input('paymentIntent'),
            'description' => $request->input('description'),
        ]);
    }
}
