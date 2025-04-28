<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QualityTest;
use App\Models\WoolBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QualityTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $woolBatch = WoolBatch::findOrFail($request->wool_batch_id);
        return view('admin.quality-tests.create', compact('woolBatch'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wool_batch_id' => 'required|exists:wool_batches,id',
            'cleanliness_score' => 'required|numeric|min:1|max:10',
            'strength_score' => 'required|numeric|min:1|max:10',
            'uniformity_score' => 'required|numeric|min:1|max:10',
            'color_score' => 'required|numeric|min:1|max:10',
            'notes' => 'nullable|string',
        ]);

        // Calculate overall score as average of all scores
        $validated['overall_score'] = ($validated['cleanliness_score'] + 
                                     $validated['strength_score'] + 
                                     $validated['uniformity_score'] + 
                                     $validated['color_score']) / 4;

        $validated['test_date'] = now();
        $validated['tested_by'] = Auth::id();

        $qualityTest = QualityTest::create($validated);

        return redirect()->route('admin.wool-batches.show', $qualityTest->wool_batch_id)
            ->with('success', 'Quality test recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QualityTest $qualityTest)
    {
        return view('admin.quality-tests.edit', compact('qualityTest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QualityTest $qualityTest)
    {
        $validated = $request->validate([
            'cleanliness_score' => 'required|numeric|min:1|max:10',
            'strength_score' => 'required|numeric|min:1|max:10',
            'uniformity_score' => 'required|numeric|min:1|max:10',
            'color_score' => 'required|numeric|min:1|max:10',
            'notes' => 'nullable|string',
        ]);

        // Calculate overall score as average of all scores
        $validated['overall_score'] = ($validated['cleanliness_score'] + 
                                     $validated['strength_score'] + 
                                     $validated['uniformity_score'] + 
                                     $validated['color_score']) / 4;

        $qualityTest->update($validated);

        return redirect()->route('admin.wool-batches.show', $qualityTest->wool_batch_id)
            ->with('success', 'Quality test updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
