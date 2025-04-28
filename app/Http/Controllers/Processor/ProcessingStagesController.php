<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\ProcessingStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcessingStagesController extends Controller
{
    public function index(Batch $batch)
    {
        $stages = $batch->processingStages()
            ->orderBy('created_at')
            ->get();

        return view('processor.batches.stages.index', compact('batch', 'stages'));
    }

    public function create(Batch $batch)
    {
        return view('processor.batches.stages.create', compact('batch'));
    }

    public function store(Request $request, Batch $batch)
    {
        $validated = $request->validate([
            'stage_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $stage = $batch->processingStages()->create($validated);

        return redirect()
            ->route('processor.batches.stages.show', ['batch' => $batch, 'stage' => $stage])
            ->with('success', 'Processing stage created successfully.');
    }

    public function show(Batch $batch, ProcessingStage $stage)
    {
        $stage->load('qualityMetrics');
        return view('processor.batches.stages.show', compact('batch', 'stage'));
    }

    public function edit(Batch $batch, ProcessingStage $stage)
    {
        return view('processor.batches.stages.edit', compact('batch', 'stage'));
    }

    public function update(Request $request, Batch $batch, ProcessingStage $stage)
    {
        $validated = $request->validate([
            'stage_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $stage->update($validated);

        return redirect()
            ->route('processor.batches.stages.show', ['batch' => $batch, 'stage' => $stage])
            ->with('success', 'Processing stage updated successfully.');
    }

    public function start(Batch $batch, ProcessingStage $stage)
    {
        $stage->start();
        return back()->with('success', 'Stage started successfully.');
    }

    public function complete(Batch $batch, ProcessingStage $stage)
    {
        $stage->complete();
        return back()->with('success', 'Stage completed successfully.');
    }

    public function fail(Batch $batch, ProcessingStage $stage, Request $request)
    {
        $validated = $request->validate([
            'notes' => 'required|string',
        ]);

        $stage->fail($validated['notes']);
        return back()->with('success', 'Stage marked as failed.');
    }
} 