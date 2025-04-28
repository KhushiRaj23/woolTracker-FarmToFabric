<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\WoolBatch;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Revenue - Handle case when there are no completed orders
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $totalRevenue = $totalRevenue === null ? 0 : (float) $totalRevenue;

        // Total Orders
        $totalOrders = Order::count();

        // Total Users
        $totalUsers = User::count();

        // Average Wool Quality
        $averageWoolQuality = WoolBatch::avg('quality') ?? 0;

        // Monthly Revenue
        $monthlyRevenue = Order::where('status', 'completed')
            ->select(
                DB::raw('strftime("%Y-%m", created_at) as month'),
                DB::raw('COALESCE(SUM(total_amount), 0) as amount')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // If no monthly revenue data, create an empty collection
        if ($monthlyRevenue->isEmpty()) {
            $monthlyRevenue = collect([
                (object)['month' => now()->format('Y-m'), 'amount' => 0]
            ]);
        }

        // Wool Quality Distribution
        $woolQualityDistribution = WoolBatch::select(
            DB::raw('CASE 
                WHEN quality >= 9 THEN "9-10"
                WHEN quality >= 7 THEN "7-8"
                WHEN quality >= 5 THEN "5-6"
                WHEN quality >= 3 THEN "3-4"
                ELSE "1-2"
            END as quality_range'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('quality_range')
        ->orderBy('quality_range')
        ->get();

        // If no wool quality data, create default ranges with zero counts
        if ($woolQualityDistribution->isEmpty()) {
            $woolQualityDistribution = collect([
                (object)['quality_range' => '1-2', 'count' => 0],
                (object)['quality_range' => '3-4', 'count' => 0],
                (object)['quality_range' => '5-6', 'count' => 0],
                (object)['quality_range' => '7-8', 'count' => 0],
                (object)['quality_range' => '9-10', 'count' => 0]
            ]);
        }

        // Recent Activities
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalUsers',
            'averageWoolQuality',
            'monthlyRevenue',
            'woolQualityDistribution',
            'recentActivities'
        ));
    }
} 