<x-distributor-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Order') }}
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-3xl font-bold mb-6 text-center text-indigo-600">Create New Order</h1>
                <form action="{{ route('distributor.orders.store') }}" method="POST" id="orderForm" class="space-y-8">
                    @csrf
                    <!-- Retailer Selection -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-semibold mb-4">Select Retailer</h3>
                        <label for="retailer_id" class="block text-sm font-medium text-gray-700 mb-1">Retailer</label>
                        <select name="retailer_id" id="retailer-select" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select a retailer</option>
                            @foreach($retailers as $retailer)
                                <option value="{{ $retailer->id }}"
                                    data-email="{{ $retailer->email }}"
                                    data-phone="{{ $retailer->phone }}"
                                    data-address="{{ $retailer->address }}"
                                    data-city="{{ $retailer->city }}"
                                    data-state="{{ $retailer->state }}"
                                    data-postal-code="{{ $retailer->postal_code }}"
                                    data-country="{{ $retailer->country }}">
                                    {{ $retailer->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="retailer-info" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><span class="font-medium">Email:</span> <span id="retailer-email" class="ml-2 text-black">-</span></div>
                            <div><span class="font-medium">Phone:</span> <span id="retailer-phone" class="ml-2 text-black">-</span></div>
                            <div class="md:col-span-2"><span class="font-medium">Address:</span> <span id="retailer-address" class="ml-2 text-black">-</span></div>
                            <div><span class="font-medium">City:</span> <span id="retailer-city" class="ml-2 text-black">-</span></div>
                            <div><span class="font-medium">State:</span> <span id="retailer-state" class="ml-2 text-black">-</span></div>
                            <div><span class="font-medium">Postal Code:</span> <span id="retailer-postal-code" class="ml-2 text-black">-</span></div>
                            <div><span class="font-medium">Country:</span> <span id="retailer-country" class="ml-2 text-black">-</span></div>
                        </div>
                    </div>
                    <!-- Order Items -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Order Items</h3>
                            <button type="button" id="add-item-btn" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Add Item</button>
                        </div>
                        <div id="orderItems" class="space-y-4">
                            <div class="order-item grid grid-cols-12 gap-4 items-end">
                                <div class="col-span-4">
                                    <label class="block text-sm font-medium text-gray-700">Product</label>
                                    <select name="items[0][product_id]" class="product-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">Select a product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                data-price="{{ $product->price }}"
                                                data-stock="{{ $product->stock_quantity }}"
                                                data-batch="{{ $product->batch ? $product->batch->name : 'N/A' }}">
                                                {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="items[0][quantity]" class="quantity-input block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="1" value="1" required>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Available Stock</label>
                                    <div class="block w-full rounded-md border-gray-300 bg-gray-50 p-2 text-sm">
                                        <span class="stock-display">-</span>
                                    </div>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Batch</label>
                                    <div class="block w-full rounded-md border-gray-300 bg-gray-50 p-2 text-sm">
                                        <span class="batch-display">-</span>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">₹</span>
                                        </div>
                                        <input type="text" class="subtotal-display pl-7 block w-full rounded-md border-gray-300 bg-gray-50" readonly value="0.00">
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <button type="button" class="remove-item-btn mt-6 inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Order Summary -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Items</label>
                                <div class="mt-1">
                                    <span id="totalItems" class="text-lg font-semibold">0</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₹</span>
                                    </div>
                                    <input type="text" id="totalAmount" class="pl-7 block w-full rounded-md border-gray-300 bg-gray-50 text-lg font-semibold" readonly value="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Create Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Retailer Info ---
    const retailerSelect = document.getElementById('retailer-select');
    const retailerFields = [
        { id: 'email', data: 'email' },
        { id: 'phone', data: 'phone' },
        { id: 'address', data: 'address' },
        { id: 'city', data: 'city' },
        { id: 'state', data: 'state' },
        { id: 'postal-code', data: 'postal-code' },
        { id: 'country', data: 'country' }
    ];
    function updateRetailerInfo() {
        const selected = retailerSelect.options[retailerSelect.selectedIndex];
        retailerFields.forEach(field => {
            let value = selected.getAttribute('data-' + field.data);
            const element = document.getElementById('retailer-' + field.id);
            if(element) {
                element.textContent = value && value !== 'null' ? value : '-';
            }
        });
    }
    retailerSelect.addEventListener('change', updateRetailerInfo);
    updateRetailerInfo();
    // --- Order Items ---
    let itemCount = 1;
    const orderItems = document.getElementById('orderItems');
    const addItemBtn = document.getElementById('add-item-btn');
    function updateProductInfo(select) {
        const orderItem = select.closest('.order-item');
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const stock = selectedOption.getAttribute('data-stock') || '-';
            const batch = selectedOption.getAttribute('data-batch') || '-';
            orderItem.querySelector('.stock-display').textContent = stock;
            orderItem.querySelector('.batch-display').textContent = batch;
            // Update subtotal
            const quantityInput = orderItem.querySelector('.quantity-input');
            updateSubtotal(quantityInput, price);
        } else {
            orderItem.querySelector('.stock-display').textContent = '-';
            orderItem.querySelector('.batch-display').textContent = '-';
            orderItem.querySelector('.subtotal-display').value = '0.00';
        }
        updateOrderSummary();
    }
    function updateSubtotal(input, priceOverride = null) {
        const orderItem = input.closest('.order-item');
        const productSelect = orderItem.querySelector('.product-select');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = priceOverride !== null ? priceOverride : (parseFloat(selectedOption.getAttribute('data-price')) || 0);
        const quantity = parseInt(input.value) || 0;
        const subtotal = (price * quantity).toFixed(2);
        orderItem.querySelector('.subtotal-display').value = subtotal;
        updateOrderSummary();
    }
    function attachOrderItemListeners(orderItem) {
        const productSelect = orderItem.querySelector('.product-select');
        const quantityInput = orderItem.querySelector('.quantity-input');
        productSelect.addEventListener('change', function() {
            updateProductInfo(productSelect);
        });
        quantityInput.addEventListener('input', function() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            updateSubtotal(quantityInput, price);
        });
        // Initial update
        updateProductInfo(productSelect);
    }
    // Attach listeners to all initial order items
    orderItems.querySelectorAll('.order-item').forEach(orderItem => {
        attachOrderItemListeners(orderItem);
        // Force update for product info and subtotal on page load
        const productSelect = orderItem.querySelector('.product-select');
        if (productSelect) updateProductInfo(productSelect);
    });
    addItemBtn.addEventListener('click', function() {
        const firstItem = orderItems.firstElementChild;
        const newItem = firstItem.cloneNode(true);
        // Update names
        newItem.querySelectorAll('[name]').forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${itemCount}]`);
        });
        // Reset values
        newItem.querySelector('.product-select').value = '';
        newItem.querySelector('.quantity-input').value = '1';
        newItem.querySelector('.stock-display').textContent = '-';
        newItem.querySelector('.batch-display').textContent = '-';
        newItem.querySelector('.subtotal-display').value = '0.00';
        orderItems.appendChild(newItem);
        attachOrderItemListeners(newItem);
        itemCount++;
        updateOrderSummary();
    });
    orderItems.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item-btn')) {
            if (orderItems.children.length > 1) {
                e.target.closest('.order-item').remove();
                updateOrderSummary();
            }
        }
    });
    function updateOrderSummary() {
        let totalItems = 0;
        let totalAmount = 0;
        orderItems.querySelectorAll('.order-item').forEach(item => {
            const quantity = parseInt(item.querySelector('.quantity-input').value) || 0;
            const subtotal = parseFloat(item.querySelector('.subtotal-display').value) || 0;
            totalItems += quantity;
            totalAmount += subtotal;
        });
        document.getElementById('totalItems').textContent = totalItems;
        // Add 10% tax
        const tax = totalAmount * 0.10;
        const totalWithTax = (totalAmount + tax).toFixed(2);
        document.getElementById('totalAmount').value = totalWithTax;
    }
});
</script>
</x-distributor-app-layout> 