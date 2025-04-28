<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShipmentController extends Controller
{
    /**
     * Display a listing of shipments.
     */
    public function index()
    {
        $shipments = Shipment::where('distributor_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('distributor.shipments.index', compact('shipments'));
    }

    /**
     * Show the form for creating a new shipment.
     */
    public function create()
    {
        $orders = Order::where('distributor_id', auth()->id())
            ->where('status', 'completed')
            ->whereDoesntHave('shipment')
            ->get();

        return view('distributor.shipments.create', compact('orders'));
    }

    /**
     * Store a newly created shipment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255|unique:shipments',
            'carrier' => 'required|string|max:255',
            'status' => 'required|string|in:pending,in_transit,delivered,cancelled',
            'estimated_delivery_date' => 'required|date',
            'shipping_method' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $shipment = Shipment::create([
                'distributor_id' => auth()->id(),
                'tracking_number' => $request->tracking_number,
                'carrier' => $request->carrier,
                'status' => $request->status,
                'estimated_delivery_date' => $request->estimated_delivery_date,
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $request->shipping_cost,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()
                ->route('distributor.shipments.show', $shipment)
                ->with('success', 'Shipment created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Shipment creation failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create shipment. Please try again.');
        }
    }

    /**
     * Display the specified shipment.
     */
    public function show(Shipment $shipment)
    {
        $this->authorize('view', $shipment);
        return view('distributor.shipments.show', compact('shipment'));
    }

    /**
     * Show the form for editing the specified shipment.
     */
    public function edit(Shipment $shipment)
    {
        $this->authorize('update', $shipment);
        return view('distributor.shipments.edit', compact('shipment'));
    }

    /**
     * Update the specified shipment.
     */
    public function update(Request $request, Shipment $shipment)
    {
        $this->authorize('update', $shipment);

        $request->validate([
            'tracking_number' => 'required|string|max:255|unique:shipments,tracking_number,' . $shipment->id,
            'carrier' => 'required|string|max:255',
            'status' => 'required|string|in:pending,in_transit,delivered,cancelled',
            'estimated_delivery_date' => 'required|date',
            'shipping_method' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            $shipment->update($request->all());

            return redirect()
                ->route('distributor.shipments.show', $shipment)
                ->with('success', 'Shipment updated successfully.');

        } catch (\Exception $e) {
            Log::error('Shipment update failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update shipment. Please try again.');
        }
    }

    /**
     * Remove the specified shipment.
     */
    public function destroy(Shipment $shipment)
    {
        $this->authorize('delete', $shipment);

        try {
            $shipment->delete();
            return redirect()
                ->route('distributor.shipments.index')
                ->with('success', 'Shipment deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Shipment deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete shipment. Please try again.');
        }
    }
} 