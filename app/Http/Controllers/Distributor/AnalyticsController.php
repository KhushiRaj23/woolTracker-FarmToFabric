<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $distributor = Auth::user();
        $timeRange = $request->input('timeRange', 30); // Default to 30 days
        $startDate = Carbon::now()->subDays($timeRange);
        
        // Total Orders
        $totalOrders = Order::where('distributor_id', $distributor->id)
            ->where('created_at', '>=', $startDate)
            ->count();

        // Total Revenue
        $totalRevenue = Order::where('distributor_id', $distributor->id)
            ->where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Average Order Value
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Top Product
        $topProduct = Product::where('distributor_id', $distributor->id)
            ->withCount(['orderItems as total_sold' => function($query) use ($startDate) {
                $query->whereHas('order', function($q) use ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                });
            }])
            ->orderBy('total_sold', 'desc')
            ->first();

        // Revenue Data for Chart (from orders)
        $revenueData = $this->getRevenueData($distributor->id, $startDate);

        // Order Status Distribution (from orders)
        $orderStatusData = $this->getOrderStatusData($distributor->id, $startDate);

        // Fallback for empty chart data
        if (empty($revenueData['labels'])) {
            $revenueData['labels'] = [date('M d')];
            $revenueData['data'] = [0];
        }
        if (empty($orderStatusData['labels'])) {
            $orderStatusData['labels'] = ['No Data'];
            $orderStatusData['data'] = [1];
        }

        // Recent Orders
        $recentOrders = Order::where('distributor_id', $distributor->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Top Products with Revenue Calculation
        $topProducts = DB::table('products')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('products.distributor_id', $distributor->id)
            ->where('orders.created_at', '>=', $startDate)
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * products.price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        return view('distributor.analytics', compact(
            'totalOrders',
            'totalRevenue',
            'averageOrderValue',
            'topProduct',
            'revenueData',
            'orderStatusData',
            'recentOrders',
            'topProducts'
        ));
    }

    private function getRevenueData($distributorId, $startDate)
    {
        $revenueData = \App\Models\Order::where('distributor_id', $distributorId)
            ->where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];
        foreach ($revenueData as $item) {
            $labels[] = \Carbon\Carbon::parse($item->date)->format('M d');
            $data[] = $item->revenue;
        }
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getOrderStatusData($distributorId, $startDate)
    {
        $statusData = \App\Models\Order::where('distributor_id', $distributorId)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $labels = [];
        $data = [];
        foreach ($statusData as $item) {
            $labels[] = ucfirst($item->status);
            $data[] = $item->count;
        }
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
