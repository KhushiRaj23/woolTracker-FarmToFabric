<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DistributorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:distributor');
    }

    public function index()
    {
        $distributor = Auth::user();
        return view('distributor.dashboard', compact('distributor'));
    }

    public function shipments()
    {
        $shipments = Shipment::where('distributor_id', Auth::id())
                             ->orderBy('dispatched_at', 'desc')
                             ->get();

        return view('distributor.shipments', compact('shipments'));
    }

    public function analytics()
    {
        $distributorId = Auth::id();

        $totalShipments = Shipment::where('distributor_id', $distributorId)->count();
        $pendingDeliveries = Shipment::where('distributor_id', $distributorId)
            ->where('status', 'pending')
            ->count();
        $completedDeliveries = Shipment::where('distributor_id', $distributorId)
            ->where('status', 'delivered')
            ->count();
    
        return view('distributor.analytics', compact(
            'totalShipments',
            'pendingDeliveries',
            'completedDeliveries'
        ));
    }

    public function dashboard()
    {
        // Get the authenticated distributor
        $distributor = Auth::guard('distributor')->user();
        $distributorId = $distributor->id;

        // Clear any cached data for this distributor
        \Cache::forget('distributor_batches_' . $distributorId);
        \Cache::forget('distributor_dashboard_' . $distributorId);

        // Add debug logging for authentication
        \Log::info('Distributor Authentication Check', [
            'distributor_id' => $distributorId,
            'distributor_name' => $distributor->name,
            'auth_check' => Auth::guard('distributor')->check(),
            'auth_user' => Auth::guard('distributor')->user()
        ]);

        // Get batch statistics with detailed logging
        $totalClaimedBatches = Batch::where('distributor_id', $distributorId)->count();
        $availableBatches = Batch::where('status', 'processed')
            ->whereNull('distributor_id')
            ->count();
        $inDistributionBatches = Batch::where('distributor_id', $distributorId)
            ->where('status', 'distributing')
            ->count();
        $distributedBatches = Batch::where('distributor_id', $distributorId)
            ->where('status', 'distributed')
            ->count();

        // Get all claimed batches for debugging
        $allClaimedBatches = Batch::where('distributor_id', $distributorId)->get();

        // Add detailed debug logging
        \Log::info('Distributor Dashboard Statistics', [
            'distributor_id' => $distributorId,
            'total_claimed_batches' => $totalClaimedBatches,
            'available_batches' => $availableBatches,
            'in_distribution_batches' => $inDistributionBatches,
            'distributed_batches' => $distributedBatches,
            'all_claimed_batches' => $allClaimedBatches->pluck('id'),
            'all_claimed_batches_details' => $allClaimedBatches->map(function($batch) {
                return [
                    'id' => $batch->id,
                    'status' => $batch->status,
                    'distributor_id' => $batch->distributor_id,
                    'distribution_date' => $batch->distribution_date
                ];
            })
        ]);

        // Get recent batches with fresh data
        $recentClaimedBatches = Batch::with(['farm', 'processor'])
            ->where('distributor_id', $distributorId)
            ->latest()
            ->take(5)
            ->get();

        $recentAvailableBatches = Batch::with(['farm', 'processor'])
            ->where('status', 'processed')
            ->whereNull('distributor_id')
            ->latest()
            ->take(5)
            ->get();

        // Get quality grade distribution
        $qualityGradeDistribution = Batch::where('distributor_id', $distributorId)
            ->selectRaw('quality_grade, count(*) as count')
            ->groupBy('quality_grade')
            ->get();

        // Get wool type distribution
        $woolTypeDistribution = Batch::where('distributor_id', $distributorId)
            ->selectRaw('wool_type, count(*) as count')
            ->groupBy('wool_type')
            ->get();

        // Get order statistics
        $totalOrders = Order::where('distributor_id', $distributorId)->count();
        $pendingOrders = Order::where('distributor_id', $distributorId)
            ->where('status', 'pending')
            ->count();
        $completedOrders = Order::where('distributor_id', $distributorId)
            ->where('status', 'completed')
            ->count();

        // Get recent orders
        $recentOrders = Order::where('distributor_id', $distributorId)
            ->latest()
            ->take(5)
            ->get();

        return view('distributor.dashboard', compact(
            'totalClaimedBatches',
            'availableBatches',
            'inDistributionBatches',
            'distributedBatches',
            'recentClaimedBatches',
            'recentAvailableBatches',
            'qualityGradeDistribution',
            'woolTypeDistribution',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'recentOrders'
        ));
    }
}
