<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['distributor', 'batch'])
            ->latest()
            ->paginate(10);
            
        $distributors = User::where('role', 'distributor')
            ->where('is_active', true)
            ->get();
            
        return view('admin.products.index', compact('products', 'distributors'));
    }

    public function show(Product $product)
    {
        $product->load(['distributor', 'batch', 'orderItems']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive,discontinued'],
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Product updated successfully.');
    }
} 