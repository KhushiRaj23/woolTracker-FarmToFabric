<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcessorController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->isProcessor()) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $processor = Auth::user()->processor;
        
        // Add debugging information
        logger('Processor Dashboard Debug:', [
            'user_id' => Auth::id(),
            'processor_id' => $processor ? $processor->id : null,
            'processor' => $processor
        ]);

        $pendingBatches = Batch::where('status', 'harvested')
            ->whereNull('processor_id')
            ->with('farm')
            ->latest()
            ->take(5)
            ->get();

        $processingBatches = Batch::where('processor_id', $processor->id)
            ->where('status', 'processing')
            ->with('farm')
            ->latest()
            ->take(5)
            ->get();

        $completedBatches = Batch::where('processor_id', $processor->id)
            ->where('status', 'processed')
            ->with('farm')
            ->latest()
            ->take(5)
            ->get();

        // Debug completed batches query
        $completedQuery = Batch::where('processor_id', $processor->id)
            ->where('status', 'processed');
            
        logger('Completed Batches Query:', [
            'sql' => $completedQuery->toSql(),
            'bindings' => $completedQuery->getBindings(),
            'count' => $completedQuery->count(),
            'batches' => $completedQuery->get()
        ]);

        $totalProcessed = $completedQuery->count();
        $totalWeight = $completedQuery->sum('weight');

        return view('processor.dashboard', compact(
            'pendingBatches',
            'processingBatches',
            'completedBatches',
            'totalProcessed',
            'totalWeight'
        ));
    }

    public function index()
    {
        $processor = Auth::user()->processor;
        
        $batches = Batch::where('processor_id', $processor->id)
            ->with('farm')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('processor.batches.index', compact('batches'));
    }

    public function myBatches()
    {
        $processor = Auth::user()->processor;
        
        $batches = Batch::where('processor_id', $processor->id)
            ->with('farm')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('processor.batches.my-batches', compact('batches'));
    }

    public function availableBatches()
    {
        $query = Batch::where(function($query) {
                $query->where('status', 'harvested')
                      ->orWhere('status', 'pending');
            })
            ->whereNull('processor_id')
            ->with('farm')
            ->orderBy('created_at', 'desc');

        // Add debugging information before executing the query
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        $finalSql = vsprintf(str_replace('?', "'%s'", $sql), $bindings);
        
        logger('Available Batches Query:', [
            'raw_sql' => $sql,
            'bindings' => $bindings,
            'final_sql' => $finalSql
        ]);

        $batches = $query->paginate(10);

        return view('processor.batches.available', compact('batches'));
    }

    public function show(Batch $batch)
    {
        // Load the batch with its relationships
        $batch->load(['farm', 'processor']);
        
        return view('processor.batches.show', compact('batch'));
    }

    public function startProcessing(Batch $batch)
    {
        $processor = Auth::user()->processor;
        
        if ($batch->status !== 'harvested' || $batch->processor_id !== null) {
            return back()->with('error', 'This batch cannot be processed.');
        }

        $batch->update([
            'processor_id' => $processor->id,
            'status' => 'processing',
            'processing_date' => now(),
        ]);

        return back()->with('success', 'Started processing batch.');
    }

    public function completeProcessing(Request $request, Batch $batch)
    {
        $processor = Auth::user()->processor;
        
        if ($batch->status !== 'processing' || $batch->processor_id !== $processor->id) {
            return back()->with('error', 'This batch cannot be marked as processed.');
        }

        $validated = $request->validate([
            'quality_grade' => 'required|in:A,B,C',
            'notes' => 'nullable|string',
        ]);

        $batch->update([
            'status' => 'processed',
            'quality_grade' => $validated['quality_grade'],
            'notes' => $validated['notes'],
        ]);

        return back()->with('success', 'Batch processing completed.');
    }
} 