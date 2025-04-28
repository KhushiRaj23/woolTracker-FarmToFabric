<x-processor-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Stage: ') }}{{ $stage->stage_name }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('processor.batches.stages.index', $batch) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Stages
                </a>
                @if($stage->status === 'in_progress')
                    <a href="{{ route('processor.batches.stages.quality.create', ['batch' => $batch, 'stage' => $stage]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Add Quality Metrics
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Stage Details -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900">Stage Details</h3>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($stage->status === 'completed') bg-green-100 text-green-800
                                        @elseif($stage->status === 'in_progress') bg-yellow-100 text-yellow-800
                                        @elseif($stage->status === 'failed') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($stage->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Started At</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $stage->started_at ? $stage->started_at->format('Y-m-d H:i') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Completed At</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $stage->completed_at ? $stage->completed_at->format('Y-m-d H:i') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Description</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $stage->description ?: 'No description provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quality Metrics -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Quality Metrics</h3>
                        @if($stage->qualityMetrics->isNotEmpty())
                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metric</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($stage->qualityMetrics as $metric)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $metric->metric_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $metric->value }} {{ $metric->unit }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($metric->grade === 'A+') bg-green-100 text-green-800
                                                        @elseif($metric->grade === 'A') bg-green-100 text-green-800
                                                        @elseif($metric->grade === 'B+') bg-yellow-100 text-yellow-800
                                                        @elseif($metric->grade === 'B') bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                        {{ $metric->grade }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $metric->created_at->format('Y-m-d H:i') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="mt-4 text-sm text-gray-500">No quality metrics recorded yet.</p>
                        @endif
                    </div>

                    <!-- Stage Actions -->
                    <div class="mt-8 flex space-x-4">
                        @if($stage->status === 'pending')
                            <form action="{{ route('processor.batches.stages.start', ['batch' => $batch, 'stage' => $stage]) }}" method="POST">
                                @csrf
                                <x-primary-button>Start Stage</x-primary-button>
                            </form>
                        @elseif($stage->status === 'in_progress')
                            <form action="{{ route('processor.batches.stages.complete', ['batch' => $batch, 'stage' => $stage]) }}" method="POST">
                                @csrf
                                <x-primary-button>Complete Stage</x-primary-button>
                            </form>
                            <button onclick="showFailModal()" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                Mark as Failed
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fail Stage Modal -->
    <div id="failModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Mark Stage as Failed</h3>
                <form action="{{ route('processor.batches.stages.fail', ['batch' => $batch, 'stage' => $stage]) }}" method="POST" class="mt-4">
                    @csrf
                    <div class="mt-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Failure Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button type="button" onclick="hideFailModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Mark as Failed
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showFailModal() {
            const modal = document.getElementById('failModal');
            modal.classList.remove('hidden');
        }

        function hideFailModal() {
            const modal = document.getElementById('failModal');
            modal.classList.add('hidden');
        }
    </script>
    @endpush
</x-processor-layout> 