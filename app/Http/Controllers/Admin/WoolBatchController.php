<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WoolBatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WoolBatchController extends Controller
{
    public function index()
    {
        $batches = WoolBatch::with(['farmer', 'collectionCenter', 'processingCenter'])
            ->latest()
            ->paginate(10);
            
        $processors = User::where('role', 'processor')
            ->where('is_active', true)
            ->get();
            
        return view('admin.wool-batches.index', compact('batches', 'processors'));
    }

    public function show(WoolBatch $woolBatch)
    {
        $woolBatch->load(['farmer', 'collectionCenter', 'processingCenter', 'qualityTests']);
        return view('admin.wool-batches.show', compact('woolBatch'));
    }

    public function edit(WoolBatch $woolBatch)
    {
        return view('admin.wool-batches.edit', compact('woolBatch'));
    }

    public function update(Request $request, WoolBatch $woolBatch)
    {
        $request->validate([
            'quality' => ['required', 'numeric', 'min:1', 'max:10'],
            'status' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $woolBatch->update($request->all());

        return redirect()->route('admin.wool-batches.show', $woolBatch)
            ->with('success', 'Wool batch updated successfully.');
    }

    public function create()
    {
        try {
            // Get active farmers from the farmers table
            $farmers = DB::table('farmers')
                ->select('id', 'name', 'email')
                ->where('active', true)
                ->get();
            \Log::info('Farmers found:', $farmers->toArray());
            
            // Get active distributors from the distributors table
            $distributors = DB::table('distributors')
                ->select('id', 'name', 'email')
                ->where('active', true)
                ->get();
            \Log::info('Distributors found:', $distributors->toArray());
            
            return view('admin.wool-batches.create', compact('farmers', 'distributors'));
        } catch (\Exception $e) {
            \Log::error('Error in WoolBatchController@create: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'farmer_id' => 'required|exists:farmers,id',
            'distributor_id' => 'required|exists:distributors,id',
            'weight' => 'required|numeric|min:0',
            'quality' => 'required|numeric|min:1|max:10',
            'price_per_kg' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,completed,distributed',
            'notes' => 'nullable|string'
        ]);

        // Generate a unique batch number
        $validated['batch_number'] = 'WB-' . strtoupper(uniqid());

        $woolBatch = WoolBatch::create($validated);

        return redirect()->route('admin.wool-batches.show', $woolBatch)
            ->with('success', 'Wool batch created successfully.');
    }
} 