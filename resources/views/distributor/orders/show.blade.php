<x-distributor-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details') }} - #{{ $order->id }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('distributor.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    {{ __('Back to Orders') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Order Status and Actions -->
                    <div class="mb-6 flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <span class="text-lg font-semibold">Status:</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        @if($order->status == 'pending')
                            <div class="flex space-x-4">
                                <form action="{{ route('distributor.orders.update', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="processing">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                        Start Processing
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Information -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1">{{ $order->customer_name }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1">{{ $order->customer_email }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1">{{ $order->customer_phone }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Shipping Information -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="font-medium text-gray-500">Address</dt>
                                    <dd class="mt-1">{{ $order->shipping_address }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">City</dt>
                                    <dd class="mt-1">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Country</dt>
                                    <dd class="mt-1">{{ $order->shipping_country }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                                <div class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    @if($item->product->batch)
                                                        {{ $item->product->batch->name }}
                                                        <div class="text-xs text-gray-500">
                                                            Batch ID: {{ $item->product->batch->id }}
                                                        </div>
                                                    @else
                                                        N/A
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($item->unit_price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($item->subtotal, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Subtotal:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Tax:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($order->tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Retailer Assignment -->
                    @if($order->status == 'processing' && !$order->retailer_id)
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Forward to Retailer</h3>
                        <form action="{{ route('distributor.orders.assign-retailer', $order) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="retailer_id" class="block text-sm font-medium text-gray-700">Select Retailer</label>
                                    <select name="retailer_id" id="retailer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Choose a retailer...</option>
                                        @foreach($retailers as $retailer)
                                            <option value="{{ $retailer->id }}">{{ $retailer->name }} - {{ $retailer->location }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes for Retailer</label>
                                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    Forward to Retailer
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                    <!-- Retailer Information (if assigned) -->
                    @if($order->retailer_id)
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Retailer Information</h3>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="font-medium text-gray-500">Retailer Name</dt>
                                    <dd class="mt-1">{{ $order->retailer->name }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Location</dt>
                                    <dd class="mt-1">{{ $order->retailer->location }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Contact</dt>
                                    <dd class="mt-1">{{ $order->retailer->contact_person }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1">{{ $order->retailer->phone }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-distributor-app-layout> 