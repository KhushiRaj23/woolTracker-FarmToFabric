<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Processor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcessorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ensure the user has a processor record
        if (!$user->processor) {
            // If no processor record exists, create one with default values
            $processor = Processor::create([
                'user_id' => $user->id,
                'company_name' => 'New Processor',
                'address' => 'Not set',
                'phone' => 'Not set',
                'capacity' => 0,
                'specialization' => 'Not set',
            ]);
        } else {
            $processor = $user->processor;
        }

        // Get batches in processing
        $processingBatches = Batch::where('processor_id', $processor->id)
            ->where('status', 'processing')
            ->with('farm')
            ->latest()
            ->take(5)
            ->get();

        // Get pending (available) batches
        $pendingBatches = Batch::where('status', 'harvested')
            ->whereNull('processor_id')
            ->with('farm')
            ->latest()
            ->take(5)
            ->get();

        // Get completed batches
        $completedBatches = Batch::where('processor_id', $processor->id)
            ->where('status', 'processed')
            ->with('farm')
            ->latest()
            ->take(5)
            ->get();

        // Get total processed count and weight
        $totalProcessed = Batch::where('processor_id', $processor->id)
            ->where('status', 'processed')
            ->count();

        $totalWeight = Batch::where('processor_id', $processor->id)
            ->where('status', 'processed')
            ->sum('weight');

        // Get recent activities
        $recentActivities = collect();

        // Add recent batch activities
        $recentBatches = Batch::where('processor_id', $processor->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($batch) {
                return (object)[
                    'type' => 'batch',
                    'description' => "Batch {$batch->batch_number} was {$batch->status}",
                    'created_at' => $batch->updated_at
                ];
            });

        // Add recent order activities
        $recentOrders = Order::where('processor_id', $processor->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                return (object)[
                    'type' => 'order',
                    'description' => "Order #{$order->id} was {$order->status}",
                    'created_at' => $order->updated_at
                ];
            });

        // Combine and sort activities
        $recentActivities = $recentBatches->concat($recentOrders)
            ->sortByDesc('created_at')
            ->take(5);

        return view('processor.dashboard', compact(
            'processingBatches',
            'pendingBatches',
            'completedBatches',
            'totalProcessed',
            'totalWeight',
            'recentActivities'
        ));
    }
} 