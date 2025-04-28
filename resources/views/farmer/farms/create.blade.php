@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Register New Farm') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('farmer.farms.store') }}" class="space-y-6" id="farmForm">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Farm Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="location" :value="__('Location')" />
                            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('location')" />
                        </div>

                        <div>
                            <x-input-label for="size" :value="__('Size (acres)')" />
                            <input 
                                type="number" 
                                id="size" 
                                name="size" 
                                step="0.01" 
                                min="0" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                value="{{ old('size', 0) }}" 
                                required 
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('size')" />
                        </div>

                        <div>
                            <x-input-label for="contact_person" :value="__('Contact Person')" />
                            <x-text-input id="contact_person" name="contact_person" type="text" class="mt-1 block w-full" :value="old('contact_person')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('contact_person')" />
                        </div>

                        <div>
                            <x-input-label for="contact_number" :value="__('Contact Number')" />
                            <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full" :value="old('contact_number')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('contact_number')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Register Farm') }}</x-primary-button>
                            <a href="{{ route('farmer.farms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('farmForm').addEventListener('submit', function(e) {
            const sizeInput = document.getElementById('size');
            const sizeValue = parseFloat(sizeInput.value);
            
            if (isNaN(sizeValue) || sizeValue < 0) {
                e.preventDefault();
                alert('Please enter a valid farm size (must be a number greater than or equal to 0)');
                sizeInput.focus();
                return false;
            }
            
            // Log form data before submission
            const formData = new FormData(this);
            console.log('Form data being submitted:', Object.fromEntries(formData));
        });
    </script>
    @endpush
@endsection 