<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FarmController extends Controller
{
    public function index()
    {
        $farms = Farm::where('farmer_id', Auth::id())->paginate(10);
        return view('farmer.farms.index', compact('farms'));
    }

    public function create()
    {
        return view('farmer.farms.create');
    }

    public function store(Request $request)
    {
        // Debug the raw request data
        \Log::info('Raw request data:', $request->all());
        
        // Debug the specific size field
        \Log::info('Size field value:', ['size' => $request->input('size')]);
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'size' => 'required|numeric|min:0',
            'contact_person' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string',
        ]);

        // Debug the validated data
        \Log::info('Validated data:', $validated);

        // Ensure size is properly formatted
        $validated['size'] = (float) $validated['size'];
        $validated['farmer_id'] = Auth::id();

        // Debug the final data being saved
        \Log::info('Final data being saved:', $validated);

        try {
            $farm = Farm::create($validated);
            \Log::info('Farm created successfully:', $farm->toArray());
        } catch (\Exception $e) {
            \Log::error('Error creating farm: ' . $e->getMessage());
            \Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
            throw $e;
        }

        return redirect()->route('farmer.farms.index')
            ->with('success', 'Farm registered successfully.');
    }

    public function show(Farm $farm)
    {
        if (Gate::denies('view', $farm)) {
            abort(403);
        }
        return view('farmer.farms.show', compact('farm'));
    }

    public function edit(Farm $farm)
    {
        if (Gate::denies('update', $farm)) {
            abort(403);
        }
        return view('farmer.farms.edit', compact('farm'));
    }

    public function update(Request $request, Farm $farm)
    {
        if (Gate::denies('update', $farm)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string',
        ]);

        $farm->update($validated);

        return redirect()->route('farmer.farms.index')
            ->with('success', 'Farm updated successfully.');
    }

    public function destroy(Farm $farm)
    {
        if (Gate::denies('delete', $farm)) {
            abort(403);
        }

        $farm->delete();

        return redirect()->route('farmer.farms.index')
            ->with('success', 'Farm deleted successfully.');
    }
} 