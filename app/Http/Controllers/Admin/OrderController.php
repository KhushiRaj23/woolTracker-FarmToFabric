<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['distributor', 'retailer'])
            ->latest()
            ->paginate(10);
            
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['distributor', 'retailer', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['items.product']);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        // If this is a status-only update
        if ($request->input('update_type') === 'status_only') {
            $validated = $request->validate([
                'status' => ['required', 'in:pending,processing,completed,cancelled'],
            ]);
        } else {
            // Full order update
            $validated = $request->validate([
                'status' => ['required', 'in:pending,processing,completed,cancelled'],
                'customer_name' => ['required', 'string', 'max:255'],
                'customer_email' => ['required', 'email', 'max:255'],
                'customer_phone' => ['required', 'string', 'max:20'],
                'shipping_address' => ['required', 'string', 'max:255'],
                'shipping_city' => ['required', 'string', 'max:255'],
                'shipping_state' => ['required', 'string', 'max:255'],
                'shipping_postal_code' => ['required', 'string', 'max:20'],
                'shipping_country' => ['required', 'string', 'max:255'],
                'notes' => ['nullable', 'string'],
            ]);
        }

        $order->update($validated);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', $request->input('update_type') === 'status_only' ? 'Order status updated successfully.' : 'Order updated successfully.');
    }
} 