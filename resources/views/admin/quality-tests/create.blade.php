<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Quality Test') }}
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
                    <form method="POST" action="{{ route('admin.quality-tests.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="wool_batch_id" value="{{ $woolBatch->id }}">

                        <!-- Cleanliness Score -->
                        <div>
                            <x-input-label for="cleanliness_score" :value="__('Cleanliness Score (1-10)')" />
                            <x-text-input id="cleanliness_score" name="cleanliness_score" type="number" step="0.1" min="1" max="10" class="mt-1 block w-full" :value="old('cleanliness_score')" required />
                            <x-input-error :messages="$errors->get('cleanliness_score')" class="mt-2" />
                        </div>

                        <!-- Strength Score -->
                        <div>
                            <x-input-label for="strength_score" :value="__('Strength Score (1-10)')" />
                            <x-text-input id="strength_score" name="strength_score" type="number" step="0.1" min="1" max="10" class="mt-1 block w-full" :value="old('strength_score')" required />
                            <x-input-error :messages="$errors->get('strength_score')" class="mt-2" />
                        </div>

                        <!-- Uniformity Score -->
                        <div>
                            <x-input-label for="uniformity_score" :value="__('Uniformity Score (1-10)')" />
                            <x-text-input id="uniformity_score" name="uniformity_score" type="number" step="0.1" min="1" max="10" class="mt-1 block w-full" :value="old('uniformity_score')" required />
                            <x-input-error :messages="$errors->get('uniformity_score')" class="mt-2" />
                        </div>

                        <!-- Color Score -->
                        <div>
                            <x-input-label for="color_score" :value="__('Color Score (1-10)')" />
                            <x-text-input id="color_score" name="color_score" type="number" step="0.1" min="1" max="10" class="mt-1 block w-full" :value="old('color_score')" required />
                            <x-input-error :messages="$errors->get('color_score')" class="mt-2" />
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
                                {{ __('Record Quality Test') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 