<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Wool Batch Details') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.wool-batches.edit', $woolBatch) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                    {{ __('Edit Batch') }}
                </a>
                <a href="{{ route('admin.wool-batches.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Batch Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Batch Information') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Batch Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $woolBatch->batch_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Farmer</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $woolBatch->farmer->name ?? 'Not Assigned' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Distributor</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $woolBatch->distributor->name ?? 'Not Assigned' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Weight</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ number_format($woolBatch->weight, 2) }} kg</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Quality Score</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ number_format($woolBatch->quality, 1) }}/10</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Price per kg</dt>
                                <dd class="mt-1 text-sm text-gray-900">₹{{ number_format($woolBatch->price_per_kg, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $woolBatch->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $woolBatch->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $woolBatch->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $woolBatch->status === 'distributed' ? 'bg-purple-100 text-purple-800' : '' }}">
                                        {{ ucfirst($woolBatch->status) }}
                                    </span>
                                </dd>
                            </div>
                            @if($woolBatch->notes)
                            <div class="col-span-full">
                                <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $woolBatch->notes }}</dd>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quality Tests -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">{{ __('Quality Tests') }}</h3>
                            <a href="{{ route('admin.quality-tests.create', ['wool_batch_id' => $woolBatch->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                {{ __('Add Quality Test') }}
                            </a>
                        </div>

                        @if($woolBatch->qualityTests->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cleanliness</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Strength</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uniformity</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overall</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tested By</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($woolBatch->qualityTests as $test)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $test->test_date->format('Y-m-d H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ number_format($test->cleanliness_score, 1) }}/10
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ number_format($test->strength_score, 1) }}/10
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ number_format($test->uniformity_score, 1) }}/10
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ number_format($test->color_score, 1) }}/10
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ number_format($test->overall_score, 1) }}/10
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $test->tester->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('admin.quality-tests.edit', $test) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No quality tests recorded yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 