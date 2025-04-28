<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Wool Batch') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.wool-batches.store') }}" class="space-y-6">
                        @csrf

                        <!-- Farmer -->
                        <div>
                            <x-input-label for="farmer_id" :value="__('Farmer')" />
                            <select id="farmer_id" name="farmer_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Farmer</option>
                                @foreach($farmers as $farmer)
                                    <option value="{{ $farmer->id }}" {{ old('farmer_id') == $farmer->id ? 'selected' : '' }}>
                                        {{ $farmer->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('farmer_id')" class="mt-2" />
                        </div>

                        <!-- Distributor -->
                        <div>
                            <x-input-label for="distributor_id" :value="__('Distributor')" />
                            <select id="distributor_id" name="distributor_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Distributor</option>
                                @foreach($distributors as $distributor)
                                    <option value="{{ $distributor->id }}" {{ old('distributor_id') == $distributor->id ? 'selected' : '' }}>
                                        {{ $distributor->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('distributor_id')" class="mt-2" />
                        </div>

                        <!-- Weight -->
                        <div>
                            <x-input-label for="weight" :value="__('Weight (kg)')" />
                            <x-text-input id="weight" name="weight" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('weight')" required />
                            <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                        </div>

                        <!-- Quality -->
                        <div>
                            <x-input-label for="quality" :value="__('Quality (1-10)')" />
                            <x-text-input id="quality" name="quality" type="number" step="0.1" min="1" max="10" class="mt-1 block w-full" :value="old('quality')" required />
                            <x-input-error :messages="$errors->get('quality')" class="mt-2" />
                        </div>

                        <!-- Price per kg -->
                        <div>
                            <x-input-label for="price_per_kg" :value="__('Price per kg')" />
                            <x-text-input id="price_per_kg" name="price_per_kg" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price_per_kg')" required />
                            <x-input-error :messages="$errors->get('price_per_kg')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="distributed" {{ old('status') == 'distributed' ? 'selected' : '' }}>Distributed</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Notes -->
                        <div>
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Create Wool Batch') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 