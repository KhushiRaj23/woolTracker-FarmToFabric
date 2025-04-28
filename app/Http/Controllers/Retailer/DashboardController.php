<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $retailerId = auth()->guard('retailer')->id();

        // Get order statistics
        $totalOrders = Order::where('retailer_id', $retailerId)->count();
        $pendingOrders = Order::where('retailer_id', $retailerId)
            ->where('status', 'pending')
            ->count();
        $completedOrders = Order::where('retailer_id', $retailerId)
            ->where('status', 'completed')
            ->count();

        // Get recent orders
        $recentOrders = Order::with('distributor')
            ->where('retailer_id', $retailerId)
            ->latest()
            ->take(5)
            ->get();

        return view('retailer.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'recentOrders'
        ));
    }
} 