<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with(['distributor', 'order'])
            ->latest()
            ->paginate(10);
            
        return view('admin.shipments.index', compact('shipments'));
    }

    public function show(Shipment $shipment)
    {
        $shipment->load(['distributor', 'order.items.product']);
        return view('admin.shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        return view('admin.shipments.edit', compact('shipment'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'status' => ['required', 'string'],
            'tracking_number' => ['required', 'string', 'unique:shipments,tracking_number,' . $shipment->id],
            'notes' => ['nullable', 'string'],
        ]);

        $shipment->update($request->all());

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Shipment updated successfully.');
    }
} 