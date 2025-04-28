<x-distributor-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('distributor.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25">
                    Edit Product
                </a>
                <a href="{{ route('distributor.products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25">
                    Back to Products
                </a>
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Product Name -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Product Name</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $product->name }}</p>
                        </div>

                        <!-- Category -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Category</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $product->category }}</p>
                        </div>

                        <!-- Price -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Price</h3>
                            <p class="mt-1 text-sm text-gray-500">₹{{ number_format($product->price, 2) }}</p>
                        </div>

                        <!-- Stock Quantity -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Stock Quantity</h3>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($product->stock_quantity > 10) bg-green-100 text-green-800
                                    @elseif($product->stock_quantity > 0) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $product->stock_quantity }}
                                </span>
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900">Description</h3>
                            <p class="mt-1 text-sm text-gray-500 whitespace-pre-line">{{ $product->description }}</p>
                        </div>

                        <!-- Created At -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Created At</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $product->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        <!-- Updated At -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Last Updated</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $product->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Delete Button -->
                    <div class="mt-6 flex justify-end">
                        <form action="{{ route('distributor.products.destroy', $product) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Are you sure you want to delete this product?')">
                                Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-distributor-app-layout> 