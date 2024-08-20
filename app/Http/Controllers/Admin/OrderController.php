<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller {
    public function index() {
        $orders = Order::with('products', 'user')->paginate(10);

        return response()->json($orders);
    }

    public function show($id) {
        $order = Order::with('products', 'user')->findOrFail($id);

        return response()->json($order);
    }

    public function processReturn(Request $request, $id) {
        $order = Order::findOrFail($id);

        // Logica per gestire il reso
        $order->status = 'returned';
        $order->save();

        return response()->json(['message' => 'Reso processato con successo', 'order' => $order]);
    }
}
