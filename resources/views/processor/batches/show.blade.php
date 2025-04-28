<x-processor-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Batch Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('processor.batches.my') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Back to My Batches
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Batch Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="font-medium text-gray-500">Batch Number</dt>
                                    <dd class="mt-1">{{ $batch->batch_number }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Farm</dt>
                                    <dd class="mt-1">{{ $batch->farm->name }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 py-1 text-sm rounded-full
                                            @if($batch->status === 'harvested') bg-yellow-100 text-yellow-800
                                            @elseif($batch->status === 'processing') bg-blue-100 text-blue-800
                                            @elseif($batch->status === 'processed') bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($batch->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Shearing Date</dt>
                                    <dd class="mt-1">{{ $batch->shearing_date ? $batch->shearing_date->format('M d, Y') : 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Wool Type</dt>
                                    <dd class="mt-1">{{ $batch->wool_type ?? 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Weight</dt>
                                    <dd class="mt-1">{{ $batch->weight ?? 'Not specified' }} kg</dd>
                                </div>
                                @if($batch->quality_grade)
                                <div>
                                    <dt class="font-medium text-gray-500">Quality Grade</dt>
                                    <dd class="mt-1">{{ $batch->quality_grade }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Processing Details</h3>
                            @if($batch->status === 'harvested')
                                <div class="mb-4">
                                    <p class="text-gray-600 mb-4">This batch is ready for processing.</p>
                                    <form action="{{ route('processor.batches.start-processing', $batch) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                            Start Processing
                                        </button>
                                    </form>
                                </div>
                            @elseif($batch->status === 'processing')
                                <div class="mb-4">
                                    <p class="text-gray-600 mb-4">Complete processing by assigning a quality grade.</p>
                                    <form action="{{ route('processor.batches.complete', $batch) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="quality_grade" class="block text-sm font-medium text-gray-700">Quality Grade</label>
                                            <select name="quality_grade" id="quality_grade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Select a grade</option>
                                                <option value="A">Grade A</option>
                                                <option value="B">Grade B</option>
                                                <option value="C">Grade C</option>
                                            </select>
                                            @error('quality_grade')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="notes" class="block text-sm font-medium text-gray-700">Processing Notes</label>
                                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                            @error('notes')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                            Complete Processing
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="mb-4">
                                    <p class="text-gray-600">Processing completed on {{ $batch->updated_at->format('M d, Y') }}</p>
                                    @if($batch->notes)
                                        <div class="mt-4">
                                            <h4 class="text-sm font-medium text-gray-700">Processing Notes</h4>
                                            <p class="mt-1 text-gray-600">{{ $batch->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-processor-app-layout> 