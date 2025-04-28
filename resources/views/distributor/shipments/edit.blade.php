@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Shipment') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('distributor.shipments.update', $shipment) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Order Information (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Order Information</label>
                            <div class="mt-1 p-4 bg-gray-50 rounded-md">
                                <p class="text-sm text-gray-900">Order #{{ $shipment->order->id }}</p>
                                <p class="text-sm text-gray-500">{{ $shipment->order->customer_name }}</p>
                            </div>
                        </div>

                        <!-- Tracking Number -->
                        <div>
                            <label for="tracking_number" class="block text-sm font-medium text-gray-700">Tracking Number</label>
                            <input type="text" name="tracking_number" id="tracking_number" 
                                value="{{ old('tracking_number', $shipment->tracking_number) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('tracking_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Carrier -->
                        <div>
                            <label for="carrier" class="block text-sm font-medium text-gray-700">Carrier</label>
                            <select name="carrier" id="carrier" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="fedex" {{ old('carrier', $shipment->carrier) == 'fedex' ? 'selected' : '' }}>FedEx</option>
                                <option value="ups" {{ old('carrier', $shipment->carrier) == 'ups' ? 'selected' : '' }}>UPS</option>
                                <option value="usps" {{ old('carrier', $shipment->carrier) == 'usps' ? 'selected' : '' }}>USPS</option>
                                <option value="dhl" {{ old('carrier', $shipment->carrier) == 'dhl' ? 'selected' : '' }}>DHL</option>
                            </select>
                            @error('carrier')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pending" {{ old('status', $shipment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_transit" {{ old('status', $shipment->status) == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                <option value="delivered" {{ old('status', $shipment->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estimated Delivery Date -->
                        <div>
                            <label for="estimated_delivery_date" class="block text-sm font-medium text-gray-700">Estimated Delivery Date</label>
                            <input type="date" name="estimated_delivery_date" id="estimated_delivery_date" 
                                value="{{ old('estimated_delivery_date', $shipment->estimated_delivery_date->format('Y-m-d')) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('estimated_delivery_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actual Delivery Date -->
                        <div>
                            <label for="delivered_at" class="block text-sm font-medium text-gray-700">Actual Delivery Date</label>
                            <input type="date" name="delivered_at" id="delivered_at" 
                                value="{{ old('delivered_at', $shipment->delivered_at ? $shipment->delivered_at->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('delivered_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $shipment->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('distributor.shipments.show', $shipment) }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Update Shipment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 