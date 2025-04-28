@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">Edit Order #{{ $order->id }}</h2>
                <a href="{{ route('distributor.orders.show', $order) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Back to Order
                </a>
            </div>
        </div>

        <form action="{{ route('distributor.orders.update', $order) }}" method="POST" id="orderForm" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- Order Status -->
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Order Status</label>
                            <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="px-4 py-5 sm:p-6 border-t border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Customer Information</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $order->customer_name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('customer_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', $order->customer_email) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('customer_email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="tel" name="customer_phone" id="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('customer_phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="px-4 py-5 sm:p-6 border-t border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Shipping Information</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" name="shipping_address" id="shipping_address" value="{{ old('shipping_address', $order->shipping_address) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('shipping_address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="shipping_city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city', $order->shipping_city) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('shipping_city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="shipping_state" class="block text-sm font-medium text-gray-700">State</label>
                            <input type="text" name="shipping_state" id="shipping_state" value="{{ old('shipping_state', $order->shipping_state) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('shipping_state')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                            <input type="text" name="shipping_postal_code" id="shipping_postal_code" value="{{ old('shipping_postal_code', $order->shipping_postal_code) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('shipping_postal_code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country</label>
                            <input type="text" name="shipping_country" id="shipping_country" value="{{ old('shipping_country', $order->shipping_country) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('shipping_country')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="px-4 py-5 sm:p-6 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Order Items</h3>
                        <button type="button" onclick="addOrderItem()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Add Item
                        </button>
                    </div>
                    <div id="orderItems" class="space-y-4">
                        @foreach($order->items as $index => $item)
                            <div class="order-item grid grid-cols-12 gap-4 items-end">
                                <div class="col-span-5">
                                    <label class="block text-sm font-medium text-gray-700">Product</label>
                                    <select name="items[{{ $index }}][product_id]" class="product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">Select a product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="items[{{ $index }}][quantity]" class="quantity-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="1" value="{{ $item->quantity }}" required>
                                </div>
                                <div class="col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="text" class="subtotal-display pl-7 block w-full rounded-md border-gray-300 bg-gray-50" readonly value="{{ number_format($item->subtotal, 2) }}">
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <button type="button" onclick="removeOrderItem(this)" class="text-red-600 hover:text-red-900">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="px-4 py-5 sm:p-6 border-t border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Order Summary</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="totalSubtotal">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax (10%):</span>
                            <span id="totalTax">${{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-semibold">
                            <span>Total:</span>
                            <span id="totalAmount">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Order
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let itemCount = {{ count($order->items) }};

    function addOrderItem() {
        const orderItems = document.getElementById('orderItems');
        const template = `
            <div class="order-item grid grid-cols-12 gap-4 items-end">
                <div class="col-span-5">
                    <label class="block text-sm font-medium text-gray-700">Product</label>
                    <select name="items[${itemCount}][product_id]" class="product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Select a product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }} - ${{ number_format($product->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="items[${itemCount}][quantity]" class="quantity-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="1" value="1" required>
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="text" class="subtotal-display pl-7 block w-full rounded-md border-gray-300 bg-gray-50" readonly value="0.00">
                    </div>
                </div>
                <div class="col-span-1">
                    <button type="button" onclick="removeOrderItem(this)" class="text-red-600 hover:text-red-900">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
        orderItems.insertAdjacentHTML('beforeend', template);
        itemCount++;
        attachEventListeners();
    }

    function removeOrderItem(button) {
        const orderItem = button.closest('.order-item');
        if (document.querySelectorAll('.order-item').length > 1) {
            orderItem.remove();
            updateTotals();
        }
    }

    function updateSubtotal(row) {
        const productSelect = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const subtotalDisplay = row.querySelector('.subtotal-display');
        
        if (productSelect.value) {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price);
            const quantity = parseInt(quantityInput.value);
            const subtotal = price * quantity;
            subtotalDisplay.value = subtotal.toFixed(2);
        } else {
            subtotalDisplay.value = '0.00';
        }
        
        updateTotals();
    }

    function updateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.subtotal-display').forEach(display => {
            subtotal += parseFloat(display.value || 0);
        });
        
        const tax = subtotal * 0.10;
        const total = subtotal + tax;
        
        document.getElementById('totalSubtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('totalTax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('totalAmount').textContent = `$${total.toFixed(2)}`;

        // Add hidden inputs for the totals
        let subtotalInput = document.querySelector('input[name="subtotal"]');
        let taxInput = document.querySelector('input[name="tax"]');
        let totalAmountInput = document.querySelector('input[name="total_amount"]');

        if (!subtotalInput) {
            subtotalInput = document.createElement('input');
            subtotalInput.type = 'hidden';
            subtotalInput.name = 'subtotal';
            document.getElementById('orderForm').appendChild(subtotalInput);
        }
        if (!taxInput) {
            taxInput = document.createElement('input');
            taxInput.type = 'hidden';
            taxInput.name = 'tax';
            document.getElementById('orderForm').appendChild(taxInput);
        }
        if (!totalAmountInput) {
            totalAmountInput = document.createElement('input');
            totalAmountInput.type = 'hidden';
            totalAmountInput.name = 'total_amount';
            document.getElementById('orderForm').appendChild(totalAmountInput);
        }

        subtotalInput.value = subtotal.toFixed(2);
        taxInput.value = tax.toFixed(2);
        totalAmountInput.value = total.toFixed(2);
    }

    function attachEventListeners() {
        document.querySelectorAll('.order-item').forEach(row => {
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');

            productSelect.addEventListener('change', () => updateSubtotal(row));
            quantityInput.addEventListener('change', () => updateSubtotal(row));
            quantityInput.addEventListener('input', () => updateSubtotal(row));
        });
    }

    // Initialize the form
    document.addEventListener('DOMContentLoaded', function() {
        // Attach event listeners to initial rows
        attachEventListeners();
        
        // Update initial totals
        updateTotals();

        // Handle form submission
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            // Validate that at least one product is selected
            const selectedProducts = document.querySelectorAll('.product-select');
            let hasSelectedProduct = false;
            selectedProducts.forEach(select => {
                if (select.value) hasSelectedProduct = true;
            });

            if (!hasSelectedProduct) {
                e.preventDefault();
                alert('Please select at least one product');
                return;
            }

            // Update totals before submission
            updateTotals();
        });
    });
</script>
@endpush
@endsection 