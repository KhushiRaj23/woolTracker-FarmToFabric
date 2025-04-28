<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Wool Batch') }}
            </h2>
            <a href="{{ route('admin.wool-batches.show', $woolBatch) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('Back to Batch') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.wool-batches.update', $woolBatch) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Batch Number -->
                        <div>
                            <x-input-label for="batch_number" :value="__('Batch Number')" />
                            <x-text-input id="batch_number" name="batch_number" type="text" class="mt-1 block w-full" :value="old('batch_number', $woolBatch->batch_number)" required readonly />
                            <x-input-error :messages="$errors->get('batch_number')" class="mt-2" />
                        </div>

                        <!-- Weight -->
                        <div>
                            <x-input-label for="weight" :value="__('Weight (kg)')" />
                            <x-text-input id="weight" name="weight" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('weight', $woolBatch->weight)" required />
                            <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                        </div>

                        <!-- Quality Score -->
                        <div>
                            <x-input-label for="quality" :value="__('Quality Score (1-10)')" />
                            <x-text-input id="quality" name="quality" type="number" step="0.1" min="1" max="10" class="mt-1 block w-full" :value="old('quality', $woolBatch->quality)" required />
                            <x-input-error :messages="$errors->get('quality')" class="mt-2" />
                        </div>

                        <!-- Price per kg -->
                        <div>
                            <x-input-label for="price_per_kg" :value="__('Price per kg (₹)')" />
                            <x-text-input id="price_per_kg" name="price_per_kg" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price_per_kg', $woolBatch->price_per_kg)" required />
                            <x-input-error :messages="$errors->get('price_per_kg')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="pending" {{ old('status', $woolBatch->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ old('status', $woolBatch->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ old('status', $woolBatch->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="distributed" {{ old('status', $woolBatch->status) == 'distributed' ? 'selected' : '' }}>Distributed</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Notes -->
                        <div>
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('notes', $woolBatch->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Update Wool Batch') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 