<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Batch') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('farmer.batches.update', $batch) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="farm_id" :value="__('Farm')" />
                                <select id="farm_id" name="farm_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Farm</option>
                                    @foreach($farms as $farm)
                                        <option value="{{ $farm->id }}" {{ old('farm_id', $batch->farm_id) == $farm->id ? 'selected' : '' }}>
                                            {{ $farm->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('farm_id')" />
                            </div>

                            <div>
                                <x-input-label for="batch_number" :value="__('Batch Number')" />
                                <x-text-input id="batch_number" name="batch_number" type="text" class="mt-1 block w-full" :value="old('batch_number', $batch->batch_number)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('batch_number')" />
                            </div>

                            <div>
                                <x-input-label for="shearing_date" :value="__('Shearing Date')" />
                                <x-text-input id="shearing_date" name="shearing_date" type="date" class="mt-1 block w-full" :value="old('shearing_date', $batch->shearing_date->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('shearing_date')" />
                            </div>

                            <div>
                                <x-input-label for="wool_type" :value="__('Wool Type')" />
                                <select id="wool_type" name="wool_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Wool Type</option>
                                    <option value="merino" {{ old('wool_type', $batch->wool_type) == 'merino' ? 'selected' : '' }}>Merino</option>
                                    <option value="crossbred" {{ old('wool_type', $batch->wool_type) == 'crossbred' ? 'selected' : '' }}>Crossbred</option>
                                    <option value="lambswool" {{ old('wool_type', $batch->wool_type) == 'lambswool' ? 'selected' : '' }}>Lambswool</option>
                                    <option value="other" {{ old('wool_type', $batch->wool_type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('wool_type')" />
                            </div>

                            <div>
                                <x-input-label for="wool_weight" :value="__('Wool Weight (kg)')" />
                                <x-text-input id="wool_weight" name="wool_weight" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('wool_weight', $batch->wool_weight)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('wool_weight')" />
                            </div>

                            <div>
                                <x-input-label for="quality_grade" :value="__('Quality Grade')" />
                                <select id="quality_grade" name="quality_grade" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Grade</option>
                                    <option value="A+" {{ old('quality_grade', $batch->quality_grade) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A" {{ old('quality_grade', $batch->quality_grade) == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B+" {{ old('quality_grade', $batch->quality_grade) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B" {{ old('quality_grade', $batch->quality_grade) == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ old('quality_grade', $batch->quality_grade) == 'C' ? 'selected' : '' }}>C</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('quality_grade')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Status</option>
                                    <option value="harvested" {{ old('status', $batch->status) == 'harvested' ? 'selected' : '' }}>Harvested</option>
                                    <option value="processing" {{ old('status', $batch->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ old('status', $batch->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="notes" :value="__('Additional Notes')" />
                            <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $batch->notes) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Batch') }}</x-primary-button>
                            <a href="{{ route('farmer.batches.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 