<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class BatchController extends Controller
{
    public function index()
    {
        $farms = Farm::where('farmer_id', Auth::id())->pluck('id');
        $batches = Batch::whereIn('farm_id', $farms)
            ->with('farm')
            ->latest()
            ->paginate(10);
        return view('farmer.batches.index', compact('batches'));
    }

    public function create()
    {
        $farms = Farm::where('farmer_id', Auth::id())->get();
        return view('farmer.batches.create', compact('farms'));
    }

    public function store(Request $request)
    {
        // Debug the incoming request
        \Log::info('Batch creation request data:', $request->all());

        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'shearing_date' => 'required|date',
            'wool_type' => 'required|string|max:255',
            'wool_weight' => 'required|numeric|min:0',
            'quality_grade' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Debug the validated data
        \Log::info('Validated batch data:', $validated);

        // Verify that the farm belongs to the authenticated farmer
        $farm = Farm::findOrFail($validated['farm_id']);
        if (Gate::denies('update', $farm)) {
            abort(403);
        }

        // Add additional fields
        $validated['batch_number'] = 'BATCH-' . Str::random(8);
        $validated['status'] = 'harvested';

        // Debug the final data
        \Log::info('Final batch data being saved:', $validated);

        try {
            $batch = Batch::create($validated);
            \Log::info('Batch created successfully:', $batch->toArray());
        } catch (\Exception $e) {
            \Log::error('Error creating batch: ' . $e->getMessage());
            \Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
            throw $e;
        }

        return redirect()->route('farmer.batches.index')
            ->with('success', 'Batch created successfully.');
    }

    public function show(Batch $batch)
    {
        if (Gate::denies('view', $batch)) {
            abort(403);
        }
        return view('farmer.batches.show', compact('batch'));
    }

    public function edit(Batch $batch)
    {
        if (Gate::denies('update', $batch)) {
            abort(403);
        }
        $farms = Farm::where('farmer_id', Auth::id())->get();
        return view('farmer.batches.edit', compact('batch', 'farms'));
    }

    public function update(Request $request, Batch $batch)
    {
        if (Gate::denies('update', $batch)) {
            abort(403);
        }

        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'wool_type' => 'required|string|max:255',
            'wool_weight' => 'required|numeric|min:0',
            'quality_grade' => 'required|string|max:255',
            'shearing_date' => 'required|date',
            'status' => 'required|in:harvested,processing,completed',
            'notes' => 'nullable|string',
        ]);

        // Debug the update
        \Log::info('Updating batch with data:', $validated);

        try {
            $batch->update($validated);
            \Log::info('Batch updated successfully:', $batch->toArray());
        } catch (\Exception $e) {
            \Log::error('Error updating batch: ' . $e->getMessage());
            throw $e;
        }

        return redirect()->route('farmer.batches.index')
            ->with('success', 'Batch updated successfully.');
    }

    public function destroy(Batch $batch)
    {
        if (Gate::denies('delete', $batch)) {
            abort(403);
        }

        $batch->delete();

        return redirect()->route('farmer.batches.index')
            ->with('success', 'Batch deleted successfully.');
    }
} 