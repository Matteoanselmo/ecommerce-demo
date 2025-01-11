<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
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

    public function getDiskSpace() {
        // Ottieni i valori in byte
        $totalSpace = disk_total_space('/');
        $freeSpace = disk_free_space('/');
        $usedSpace = $totalSpace - $freeSpace;

        // Converti in MB
        $totalSpaceMB = $totalSpace / (1024 * 1024);
        $freeSpaceMB = $freeSpace / (1024 * 1024);
        $usedSpaceMB = $usedSpace / (1024 * 1024);

        // Restituire i dati come una risposta JSON in MB
        return response()->json([
            'total' => round($totalSpaceMB, 2),  // Arrotondamento a 2 decimali
            'free' => round($freeSpaceMB, 2),
            'used' => round($usedSpaceMB, 2),
        ]);
    }

    public function getUsersCount() {
        $userCount = User::where('role', 'user')->count();

        return response()->json($userCount);
    }

    public function getOrdersCountByStatus() {
        // Esegui una query per raggruppare gli ordini per 'status' e contarli
        $ordersCount = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Restituisci i dati in formato JSON
        return response()->json($ordersCount);
    }

    public function getPaymentMethodsUsage() {
        $paymentMethods = Order::select('payment', DB::raw('COUNT(*) as count'))
            ->groupBy('payment')
            ->get();

        $totalOrders = Order::count();

        // Calcolo delle percentuali
        $paymentMethodsPercentage = $paymentMethods->map(function ($method) use ($totalOrders) {
            return [
                'payment' => $method->payment,
                'count' => $method->count,
                'percentage' => round(($method->count / $totalOrders) * 100, 2)
            ];
        });
        \Log::info($paymentMethodsPercentage);
        return response()->json($paymentMethodsPercentage);
    }

    public function getRevenueByPeriod(Request $request) {
        $period = $request->input('period', 'monthly'); // 'monthly' o 'yearly'

        if ($period === 'monthly') {
            $revenue = Order::select(
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year')
            )
                ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        } else {
            $revenue = Order::select(
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('YEAR(created_at) as year')
            )
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->orderBy('year')
                ->get();
        }

        return response()->json([
            'revenue_by_period' => $revenue
        ]);
    }
}
