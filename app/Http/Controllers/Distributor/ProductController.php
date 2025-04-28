<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:distributor');
    }

    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::where('distributor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('distributor.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('distributor.products.create');
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
        ]);

        $product = new Product($validated);
        $product->distributor_id = Auth::id();
        $product->status = 'active'; // Set default status
        $product->save();

        return redirect()
            ->route('distributor.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        if (Gate::denies('view', $product)) {
            abort(403);
        }
        return view('distributor.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        if (Gate::denies('update', $product)) {
            abort(403);
        }
        return view('distributor.products.edit', compact('product'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        if (Gate::denies('update', $product)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
        ]);

        $product->update($validated);

        return redirect()
            ->route('distributor.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        if (Gate::denies('delete', $product)) {
            abort(403);
        }
        
        $product->delete();

        return redirect()
            ->route('distributor.products.index')
            ->with('success', 'Product deleted successfully.');
    }
} 