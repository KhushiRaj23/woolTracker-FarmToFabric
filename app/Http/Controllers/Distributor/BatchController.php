<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::with(['farm', 'processor'])
            ->where('status', 'processed')
            ->whereNull('distributor_id')
            ->latest()
            ->paginate(10);

        return view('distributor.batches.index', compact('batches'));
    }

    public function show(Batch $batch)
    {
        $distributor = Auth::guard('distributor')->user();
        if (!$distributor) {
            abort(403, 'Unauthorized action.');
        }

        // Allow viewing if:
        // 1. The batch is processed and not claimed by any distributor
        // 2. The batch is claimed by the current distributor
        if (!($batch->status === 'processed' && $batch->distributor_id === null) && 
            !($batch->distributor_id === $distributor->id)) {
            \Log::warning('Unauthorized batch access attempt', [
                'batch_id' => $batch->id,
                'distributor_id' => $distributor->id,
                'batch_status' => $batch->status,
                'batch_distributor_id' => $batch->distributor_id
            ]);
            abort(403, 'Unauthorized action.');
        }

        $batch->load(['farm', 'processor']);
        
        // Add debug logging
        \Log::info('Batch show accessed', [
            'batch_id' => $batch->id,
            'distributor_id' => $distributor->id,
            'batch_status' => $batch->status,
            'batch_distributor_id' => $batch->distributor_id
        ]);

        return view('distributor.batches.show', compact('batch'));
    }

    public function claim(Batch $batch)
    {
        // Get the authenticated distributor
        $distributor = Auth::guard('distributor')->user();
        if (!$distributor) {
            \Log::error('No authenticated distributor found');
            return back()->with('error', 'Authentication error. Please log in again.');
        }
        
        $distributorId = $distributor->id;
        
        \Log::info('Attempting to claim batch', [
            'batch_id' => $batch->id,
            'batch_number' => $batch->batch_number,
            'distributor_id' => $distributorId,
            'distributor_name' => $distributor->name,
            'current_status' => $batch->status,
            'current_distributor_id' => $batch->distributor_id,
            'auth_check' => Auth::guard('distributor')->check()
        ]);

        if ($batch->status !== 'processed' || $batch->distributor_id !== null) {
            \Log::warning('Batch claim failed - invalid status or already claimed', [
                'batch_id' => $batch->id,
                'status' => $batch->status,
                'distributor_id' => $batch->distributor_id,
                'validation_failed' => [
                    'status_check' => $batch->status !== 'processed',
                    'distributor_check' => $batch->distributor_id !== null
                ]
            ]);
            return back()->with('error', 'This batch is not available for distribution.');
        }

        try {
            // Start a database transaction
            \DB::beginTransaction();

            // Update the batch
            $batch->distributor_id = $distributorId;
            $batch->distribution_date = now();
            $batch->status = 'distributing';
            $batch->save();

            // Verify the update
            $updatedBatch = Batch::where('id', $batch->id)
                ->where('distributor_id', $distributorId)
                ->where('status', 'distributing')
                ->first();

            if (!$updatedBatch) {
                \DB::rollBack();
                \Log::error('Batch claim verification failed - could not find updated batch', [
                    'batch_id' => $batch->id,
                    'distributor_id' => $distributorId
                ]);
                return back()->with('error', 'Failed to claim batch. Please try again.');
            }

            // Log the successful update
            \Log::info('Batch claim completed successfully', [
                'batch_id' => $batch->id,
                'new_status' => $updatedBatch->status,
                'new_distributor_id' => $updatedBatch->distributor_id,
                'distribution_date' => $updatedBatch->distribution_date,
                'total_claimed_batches' => Batch::where('distributor_id', $distributorId)->count()
            ]);

            // Commit the transaction
            \DB::commit();

            // Clear any cache
            \Cache::tags(['batches', 'distributor_' . $distributorId])->flush();

            return redirect()->route('distributor.dashboard')
                ->with('success', 'Batch #' . $batch->batch_number . ' has been claimed successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Batch claim failed - database error', [
                'batch_id' => $batch->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to claim batch. Please try again.');
        }
    }

    public function myBatches()
    {
        $batches = Batch::with(['farm', 'processor'])
            ->where('distributor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('distributor.batches.my-batches', compact('batches'));
    }
} 