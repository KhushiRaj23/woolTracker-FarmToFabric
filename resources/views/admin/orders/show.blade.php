<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details') }} #{{ $order->id }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    {{ __('Back to Orders') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Order Status -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Status</h3>
                        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="flex items-center space-x-4">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="update_type" value="status_only">
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Update Status
                            </button>
                        </form>
                    </div>

                    <!-- Order Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Information -->
                        <div class="bg-white rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->customer_name }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->customer_email }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->customer_phone }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Shipping Information -->
                        <div class="bg-white rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->shipping_address }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">City</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->shipping_city }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">State</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->shipping_state }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Postal Code</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->shipping_postal_code }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Country</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->shipping_country }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $item->product->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ₹{{ number_format($item->unit_price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ₹{{ number_format($item->subtotal, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Subtotal:</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">₹{{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Tax:</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">₹{{ number_format($order->tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Total:</td>
                                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹{{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    @if($order->notes)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 