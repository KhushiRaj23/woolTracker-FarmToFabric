<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    public function myBatches()
    {
        // Get batches that belong to the current processor
        $batches = Batch::with('farm')
            ->where('processor_id', Auth::id())
            ->whereIn('status', ['processing', 'completed'])
            ->latest()
            ->paginate(10);

        return view('processor.batches.my-batches', compact('batches'));
    }

    public function availableBatches()
    {
        // Get only harvested batches that aren't assigned to any processor
        $batches = Batch::with('farm')
            ->where('status', 'harvested')
            ->whereNull('processor_id')
            ->latest()
            ->paginate(10);

        return view('processor.batches.available', compact('batches'));
    }

    public function show(Batch $batch)
    {
        // Check if the batch is available for the processor
        if (!$this->canAccessBatch($batch)) {
            abort(403);
        }

        return view('processor.batches.show', compact('batch'));
    }

    public function startProcessing(Batch $batch)
    {
        // Check if the batch is available and not already being processed
        if ($batch->status !== 'harvested' || $batch->processor_id !== null) {
            return back()->with('error', 'This batch is not available for processing.');
        }

        $batch->update([
            'status' => 'processing',
            'processor_id' => Auth::id(),
            'processing_date' => now()
        ]);

        return redirect()->route('processor.batches.my')
            ->with('success', 'Started processing the batch.');
    }

    public function completeProcessing(Request $request, Batch $batch)
    {
        // Verify the processor owns this batch
        if ($batch->processor_id !== Auth::id()) {
            abort(403);
        }

        // Validate completion data
        $validated = $request->validate([
            'quality_grade' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $batch->update([
            'status' => 'completed',
            'quality_grade' => $validated['quality_grade'],
            'notes' => $validated['notes'],
            'completed_date' => now()
        ]);

        return back()->with('success', 'Batch processing completed successfully.');
    }

    protected function canAccessBatch(Batch $batch)
    {
        return $batch->status === 'harvested' || 
               ($batch->processor_id === Auth::id() && in_array($batch->status, ['processing', 'completed']));
    }
} 