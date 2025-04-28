<x-distributor-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Batch Details') }} - {{ $batch->batch_number }}
            </h2>
            <div>
                <a href="{{ route('distributor.batches.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    {{ __('Back to Batches') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Farm Information</h3>
                                <div class="mt-2 space-y-2">
                                    <p><span class="font-medium">Name:</span> {{ $batch->farm->name }}</p>
                                    <p><span class="font-medium">Location:</span> {{ $batch->farm->location }}</p>
                                    <p><span class="font-medium">Contact:</span> {{ $batch->farm->contact_person }}</p>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Processor Information</h3>
                                <div class="mt-2 space-y-2">
                                    <p><span class="font-medium">Company:</span> {{ $batch->processor->company_name }}</p>
                                    <p><span class="font-medium">Specialization:</span> {{ $batch->processor->specialization }}</p>
                                    <p><span class="font-medium">Contact:</span> {{ $batch->processor->phone }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Batch Details</h3>
                                <div class="mt-2 space-y-2">
                                    <p><span class="font-medium">Batch Number:</span> {{ $batch->batch_number }}</p>
                                    <p><span class="font-medium">Wool Type:</span> {{ $batch->wool_type }}</p>
                                    <p><span class="font-medium">Weight:</span> {{ $batch->weight }} kg</p>
                                    <p>
                                        <span class="font-medium">Quality Grade:</span>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $batch->quality_grade === 'A' ? 'bg-green-100 text-green-800' : 
                                               ($batch->quality_grade === 'B' ? 'bg-yellow-100 text-yellow-800' : 
                                                'bg-red-100 text-red-800') }}">
                                            Grade {{ $batch->quality_grade }}
                                        </span>
                                    </p>
                                    <p><span class="font-medium">Processing Date:</span> {{ $batch->processing_date ? $batch->processing_date->format('M d, Y') : 'N/A' }}</p>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Additional Information</h3>
                                <div class="mt-2 space-y-2">
                                    <p><span class="font-medium">Notes:</span></p>
                                    <p class="text-gray-600">{{ $batch->notes ?: 'No additional notes' }}</p>
                                </div>
                            </div>

                            @if(!$batch->distributor_id)
                                <div class="mt-6">
                                    <form action="{{ route('distributor.batches.claim', $batch) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                            {{ __('Claim Batch for Distribution') }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-distributor-app-layout> 