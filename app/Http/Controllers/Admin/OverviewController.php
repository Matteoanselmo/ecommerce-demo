<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OverviewController extends Controller {
    public function getSalesData() {
        $salesData = Order::select(
            DB::raw('SUM(total_amount) as total_sales'),
            DB::raw('MONTH(created_at) as month')
        )
            ->groupBy('month')
            ->get();

        return response()->json($salesData);
    }

    public function getTrafficData() {
        // Simulazione dei dati di traffico (in un'app reale, li prenderesti da Google Analytics o un altro servizio)
        $trafficData = [
            ['month' => 'Gen', 'visitors' => 1000],
            ['month' => 'Feb', 'visitors' => 3000],
            ['month' => 'Mar', 'visitors' => 2000],
            // altri mesi
        ];

        return response()->json($trafficData);
    }

    public function getTopProductsData() {
        $topProducts = Product::withSum('orders as total_sales', 'order_product.order_quantity')
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get(['name', 'total_sales']);

        return response()->json($topProducts);
    }
}
