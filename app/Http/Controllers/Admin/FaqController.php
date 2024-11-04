<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Product;
use Illuminate\Http\Request;

class FaqController extends Controller {

    public function saveFaqs(Request $request, $productId) {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'message' => 'Prodotto non trovato',
                'color' => 'danger'
            ], 404);
        }

        // Valida le FAQ inviate nel request
        $validatedData = $request->validate([
            'faqs' => 'required|array',
            'faqs.*.question' => 'required|string|max:255',
            'faqs.*.answer' => 'required|string|max:255',
        ]);

        // Rimuove tutte le FAQ esistenti per il prodotto
        $product->faqs()->delete();

        // Inserisce le nuove FAQ
        foreach ($validatedData['faqs'] as $faqData) {
            $product->faqs()->create($faqData);
        }

        return response()->json([
            'message' => 'FAQ salvate con successo',
            'color' => 'success'
        ], 200);
    }

    public function deleteFaq($faqId) {
        $faq = Faq::find($faqId);

        if (!$faq) {
            return response()->json([
                'message' => 'FAQ non trovata',
                'color' => 'danger'
            ], 404);
        }

        $faq->delete();

        return response()->json([
            'message' => 'FAQ eliminata con successo',
            'color' => 'success'
        ], 200);
    }
}
