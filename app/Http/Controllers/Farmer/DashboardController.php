<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Models\Batch;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $farmerId = auth()->guard('farmer')->id();
        
        // Basic stats
        $totalFarms = Farm::where('farmer_id', $farmerId)->count();
        $activeBatches = Batch::whereHas('farm', function($query) use ($farmerId) {
            $query->where('farmer_id', $farmerId);
        })->where('status', 'harvested')->count();
        $totalWoolWeight = Batch::whereHas('farm', function($query) use ($farmerId) {
            $query->where('farmer_id', $farmerId);
        })->sum('wool_weight');

        // Recent farms and batches
        $recentFarms = Farm::where('farmer_id', $farmerId)
            ->latest()
            ->take(5)
            ->get();
        $recentBatches = Batch::whereHas('farm', function($query) use ($farmerId) {
            $query->where('farmer_id', $farmerId);
        })->with('farm')
            ->latest()
            ->take(5)
            ->get();

        // Wool Production Over Time (Last 6 months)
        $productionData = [];
        $productionLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyWeight = Batch::whereHas('farm', function($query) use ($farmerId) {
                $query->where('farmer_id', $farmerId);
            })->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('wool_weight');
            
            $productionData[] = $monthlyWeight;
            $productionLabels[] = $date->format('M Y');
        }

        // Wool Type Distribution
        $woolTypes = Batch::whereHas('farm', function($query) use ($farmerId) {
            $query->where('farmer_id', $farmerId);
        })->select('wool_type')
            ->selectRaw('count(*) as count')
            ->groupBy('wool_type')
            ->get();
        
        $woolTypeLabels = $woolTypes->pluck('wool_type');
        $woolTypeData = $woolTypes->pluck('count');

        // Farm Sizes Distribution
        $farmSizes = Farm::where('farmer_id', $farmerId)
            ->whereNotNull('size')
            ->where('size', '>', 0)
            ->selectRaw('CAST(size/10 AS INTEGER)*10 as size_range')
            ->selectRaw('count(*) as count')
            ->groupBy('size_range')
            ->orderBy('size_range')
            ->get();
        
        // If no farms with size data, create a default entry
        if ($farmSizes->isEmpty()) {
            $farmSizes = collect([(object)[
                'size_range' => 0,
                'count' => $totalFarms
            ]]);
        }
        
        $farmSizeLabels = $farmSizes->map(function($item) {
            return $item->size_range . '-' . ($item->size_range + 9) . ' acres';
        });
        $farmSizeData = $farmSizes->pluck('count');

        // Status Distribution
        $statuses = Batch::whereHas('farm', function($query) use ($farmerId) {
            $query->where('farmer_id', $farmerId);
        })->select('status')
            ->selectRaw('count(*) as count')
            ->groupBy('status')
            ->get();
        
        $statusLabels = $statuses->pluck('status');
        $statusData = $statuses->pluck('count');

        return view('farmer.dashboard', compact(
            'totalFarms',
            'activeBatches',
            'totalWoolWeight',
            'recentFarms',
            'recentBatches',
            'productionData',
            'productionLabels',
            'woolTypeLabels',
            'woolTypeData',
            'farmSizeLabels',
            'farmSizeData',
            'statusLabels',
            'statusData'
        ));
    }
} 