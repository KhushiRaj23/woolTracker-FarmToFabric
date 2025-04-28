@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shipment Details') }}
        </h2>
        <div class="flex space-x-4">
            <a href="{{ route('distributor.shipments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
                Back to Shipments
            </a>
            @if($shipment->status === 'pending')
                <a href="{{ route('distributor.shipments.edit', $shipment) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Edit Shipment
                </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Shipment Status -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Status</h3>
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                            @if($shipment->status === 'delivered') bg-green-100 text-green-800
                            @elseif($shipment->status === 'in_transit') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                        </span>
                    </div>

                    <!-- Shipment Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Shipment Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tracking Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $shipment->tracking_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Carrier</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($shipment->carrier) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dispatch Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $shipment->dispatched_at ? $shipment->dispatched_at->format('F j, Y') : 'Not dispatched' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estimated Delivery</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $shipment->estimated_delivery_date->format('F j, Y') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Actual Delivery</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $shipment->delivered_at ? $shipment->delivered_at->format('F j, Y') : 'Pending' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Order Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="{{ route('distributor.orders.show', $shipment->order) }}" class="text-indigo-600 hover:text-indigo-900">
                                            #{{ $shipment->order->id }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Customer Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $shipment->order->customer_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Shipping Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $shipment->order->shipping_address }}<br>
                                        {{ $shipment->order->shipping_city }}, {{ $shipment->order->shipping_state }} {{ $shipment->order->shipping_postal_code }}<br>
                                        {{ $shipment->order->shipping_country }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($shipment->notes)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Notes</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-700">{{ $shipment->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Tracking History -->
                    @if($shipment->tracking_history)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tracking History</h3>
                            <div class="flow-root">
                                <ul role="list" class="-mb-8">
                                    @foreach($shipment->tracking_history as $history)
                                        <li>
                                            <div class="relative pb-8">
                                                @if(!$loop->last)
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white 
                                                            @if($history->status === 'delivered') bg-green-500
                                                            @elseif($history->status === 'in_transit') bg-blue-500
                                                            @else bg-gray-500
                                                            @endif">
                                                            <!-- Icon -->
                                                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500">{{ $history->description }}</p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            <time datetime="{{ $history->timestamp }}">{{ $history->timestamp->format('M j, Y H:i') }}</time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 