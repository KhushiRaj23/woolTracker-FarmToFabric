<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Retailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of orders.
     */
    public function index()
    {
        $orders = Order::with(['items.product'])
            ->where('distributor_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('distributor.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $products = Product::with('batch')
            ->where('distributor_id', auth()->id())
            ->available()
            ->get();
        
        $retailers = Retailer::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country']);
        
        return view('distributor.orders.create', compact('products', 'retailers'));
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'retailer_id' => 'required|exists:retailers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:9999',
        ]);

        try {
            DB::beginTransaction();

            $distributorId = auth()->id();
            $totalAmount = 0;

            // Get retailer details
            $retailer = Retailer::findOrFail($request->retailer_id);
            \Log::debug('Creating order for retailer:', $retailer->toArray());
            \Log::debug('Order items:', $request->items);

            // Validate products and check stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                \Log::debug('Processing product:', $product->toArray());
                
                // Verify product ownership
                if (!$product->belongsToDistributor($distributorId)) {
                    throw new \Exception('Invalid product selection.');
                }

                // Verify product availability
                if (!$product->isAvailable()) {
                    throw new \Exception("Product {$product->name} is not available.");
                }

                // Verify stock quantity
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}.");
                }
            }

            // Create the order
            $order = Order::create([
                'distributor_id' => $distributorId,
                'retailer_id' => $retailer->id,
                'customer_name' => $retailer->name,
                'customer_email' => $retailer->email,
                'customer_phone' => $retailer->phone,
                'shipping_address' => $retailer->address,
                'shipping_city' => $retailer->city,
                'shipping_state' => $retailer->state,
                'shipping_postal_code' => $retailer->postal_code,
                'shipping_country' => $retailer->country,
                'status' => 'pending',
                'subtotal' => 0,
                'tax' => 0,
                'total_amount' => 0,
            ]);

            // Create order items and update stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                // Update stock
                if (!$product->updateStock(-$item['quantity'])) {
                    throw new \Exception("Failed to update stock for {$product->name}.");
                }
            }

            // Update order with total amount including tax
            $tax = $totalAmount * 0.10;
            $order->update([
                'subtotal' => $totalAmount,
                'tax' => $tax,
                'total_amount' => $totalAmount + $tax,
            ]);

            DB::commit();

            return redirect()
                ->route('distributor.orders.show', $order)
                ->with('success', 'Order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Ensure the distributor can only view their own orders
        if ($order->distributor_id !== auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Get available retailers for assignment
        $retailers = Retailer::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Load order with relationships including batch information
        $order->load(['items.product.batch']);

        return view('distributor.orders.show', compact('order', 'retailers'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        
        $products = Product::where('distributor_id', auth()->id())
            ->available()
            ->get();
            
        $order->load(['items.product']);
        return view('distributor.orders.edit', compact('order', 'products'));
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:9999',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // First, return stock for existing items
            foreach ($order->items as $existingItem) {
                $product = $existingItem->product;
                $product->stock_quantity += $existingItem->quantity;
                $product->save();
            }

            // Update order details
            $order->update([
                'status' => $validated['status'],
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_postal_code' => $validated['shipping_postal_code'],
                'shipping_country' => $validated['shipping_country'],
                'subtotal' => $validated['subtotal'],
                'tax' => $validated['tax'],
                'total_amount' => $validated['total_amount'],
            ]);

            // Delete existing items
            $order->items()->delete();

            // Create new order items and update stock
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Verify product ownership
                if (!$product->belongsToDistributor(auth()->id())) {
                    throw new \Exception('Invalid product selection.');
                }

                // Verify product availability
                if (!$product->isAvailable()) {
                    throw new \Exception("Product {$product->name} is not available.");
                }

                // Verify stock quantity
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}.");
                }

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);

                // Update stock
                if (!$product->updateStock(-$item['quantity'])) {
                    throw new \Exception("Failed to update stock for {$product->name}.");
                }
            }

            DB::commit();

            return redirect()
                ->route('distributor.orders.show', $order)
                ->with('success', 'Order updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order update failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel the specified order.
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        if (!$order->isCancellable()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        try {
            $order->update(['status' => 'cancelled']);
            return redirect()
                ->route('distributor.orders.index')
                ->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            Log::error('Order cancellation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel order. Please try again.');
        }
    }

    /**
     * Assign a retailer to process the order
     */
    public function assignRetailer(Request $request, Order $order)
    {
        // Validate the request
        $validated = $request->validate([
            'retailer_id' => 'required|exists:retailers,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Ensure the distributor owns this order
        if ($order->distributor_id !== auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure the order is in a valid state for assignment
        if ($order->status !== 'processing') {
            return back()->with('error', 'Order must be in processing state to be assigned to a retailer.');
        }

        // Ensure the order isn't already assigned
        if ($order->retailer_id) {
            return back()->with('error', 'Order is already assigned to a retailer.');
        }

        try {
            DB::beginTransaction();

            // Update the order
            $order->update([
                'retailer_id' => $validated['retailer_id'],
                'retailer_notes' => $validated['notes'],
                'retailer_assigned_at' => now(),
            ]);

            // You might want to send a notification to the retailer here
            // Notification::send($retailer, new OrderAssignedNotification($order));

            DB::commit();

            return redirect()->route('distributor.orders.show', $order)
                ->with('success', 'Order has been successfully assigned to the retailer.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to assign retailer to order: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to assign retailer. Please try again.');
        }
    }
}