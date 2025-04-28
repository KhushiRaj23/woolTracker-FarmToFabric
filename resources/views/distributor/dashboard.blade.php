<x-distributor-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Distributor Dashboard') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('distributor.batches.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    View Available Batches
                </a>
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Batch Statistics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-lg font-semibold mb-2">Total Claimed Batches</div>
                        <div class="text-3xl font-bold text-indigo-600">
                            {{ $totalClaimedBatches }}
                            @if(session('success'))
                                <div class="text-sm font-normal text-green-600 mt-2">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-lg font-semibold mb-2">Available Batches</div>
                        <div class="text-3xl font-bold text-green-600">{{ $availableBatches }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-lg font-semibold mb-2">In Distribution</div>
                        <div class="text-3xl font-bold text-blue-600">{{ $inDistributionBatches }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-lg font-semibold mb-2">Distributed</div>
                        <div class="text-3xl font-bold text-purple-600">{{ $distributedBatches }}</div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Claimed Batches -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Claimed Batches</h3>
                        @if($recentClaimedBatches->isEmpty())
                            <p class="text-gray-500">No claimed batches yet.</p>
                        @else
                            <div class="space-y-4">
                                @foreach($recentClaimedBatches as $batch)
                                    <div class="border-b pb-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="font-medium">Batch #{{ $batch->batch_number }}</div>
                                                <div class="text-sm text-gray-500">From: {{ $batch->farm->name }}</div>
                                                <div class="text-sm text-gray-500">Processed by: {{ $batch->processor->company_name }}</div>
                                            </div>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $batch->quality_grade === 'A' ? 'bg-green-100 text-green-800' : 
                                                   ($batch->quality_grade === 'B' ? 'bg-yellow-100 text-yellow-800' : 
                                                    'bg-red-100 text-red-800') }}">
                                                Grade {{ $batch->quality_grade }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('distributor.batches.my') }}" class="text-indigo-600 hover:text-indigo-900">View all claimed batches →</a>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Available Batches -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Batches</h3>
                        @if($recentAvailableBatches->isEmpty())
                            <p class="text-gray-500">No available batches at the moment.</p>
                        @else
                            <div class="space-y-4">
                                @foreach($recentAvailableBatches as $batch)
                                    <div class="border-b pb-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="font-medium">Batch #{{ $batch->batch_number }}</div>
                                                <div class="text-sm text-gray-500">From: {{ $batch->farm->name }}</div>
                                                <div class="text-sm text-gray-500">Weight: {{ $batch->weight }} kg</div>
                                            </div>
                                            <a href="{{ route('distributor.batches.show', $batch) }}" 
                                               class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('distributor.batches.index') }}" class="text-indigo-600 hover:text-indigo-900">View all available batches →</a>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Quality Grade Distribution -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quality Grade Distribution</h3>
                        @if($qualityGradeDistribution->isEmpty())
                            <p class="text-gray-500">No quality grade data available.</p>
                        @else
                            <div class="space-y-4">
                                @foreach($qualityGradeDistribution as $grade)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium">Grade {{ $grade->quality_grade }}</span>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $grade->quality_grade === 'A' ? 'bg-green-100 text-green-800' : 
                                               ($grade->quality_grade === 'B' ? 'bg-yellow-100 text-yellow-800' : 
                                                'bg-red-100 text-red-800') }}">
                                            {{ $grade->count }} batches
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Recent Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Orders</h3>
                        @if($recentOrders->isEmpty())
                            <p class="text-gray-500">No orders yet.</p>
                        @else
                            <div class="space-y-4">
                                @foreach($recentOrders as $order)
                                    <div class="border-b pb-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="font-medium">Order #{{ $order->id }}</div>
                                                <div class="text-sm text-gray-500">Customer: {{ $order->customer_name }}</div>
                                                <div class="text-sm text-gray-500">Amount: ₹{{ number_format($order->total_amount, 2) }}</div>
                                            </div>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                    'bg-blue-100 text-blue-800') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('distributor.orders.index') }}" class="text-indigo-600 hover:text-indigo-900">View all orders →</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-distributor-app-layout>
