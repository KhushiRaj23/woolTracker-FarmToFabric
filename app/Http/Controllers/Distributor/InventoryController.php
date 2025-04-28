<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth:distributor']);
    }

    /**
     * Display the distributor's inventory.
     */
    public function index()
    {
        $products = Product::where('distributor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('distributor.inventory.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $this->authorize('create', Product::class);
        return view('distributor.inventory.create');
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('distributor_id', Auth::id());
                }),
            ],
            'category' => 'required|string|in:raw_wool,processed_wool,yarn,fabric',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01|decimal:0,2',
            'stock_quantity' => 'required|integer|min:0|max:999999',
            'status' => 'required|string|in:active,inactive,discontinued',
        ]);

        $validated['distributor_id'] = Auth::id();

        try {
            Product::create($validated);

            return redirect()
                ->route('distributor.inventory.index')
                ->with('success', 'Product added successfully.');
        } catch (\Exception $e) {
            Log::error('Product creation failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create product. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        return view('distributor.inventory.edit', compact('product'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('distributor_id', Auth::id());
                })->ignore($product->id),
            ],
            'category' => 'required|string|in:raw_wool,processed_wool,yarn,fabric',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01|decimal:0,2',
            'stock_quantity' => 'required|integer|min:0|max:999999',
            'status' => 'required|string|in:active,inactive,discontinued',
        ]);

        try {
            $product->update($validated);

            return redirect()
                ->route('distributor.inventory.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            Log::error('Product update failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update product. Please try again.');
        }
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        
        try {
            $product->delete();
            return redirect()
                ->route('distributor.inventory.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Product deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Cannot delete product with existing orders.');
        }
    }
} 