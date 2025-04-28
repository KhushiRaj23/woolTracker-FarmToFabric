<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(): View
    {
        $retailerId = auth()->guard('retailer')->id();
        
        $orders = Order::with('distributor')
            ->where('retailer_id', $retailerId)
            ->latest()
            ->paginate(10);

        return view('retailer.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        $this->authorize('view', $order);
        
        $order->load('distributor', 'items.product');
        
        return view('retailer.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Ensure the retailer can only update their own orders
        if ($order->retailer_id !== auth()->guard('retailer')->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $order->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }
} 