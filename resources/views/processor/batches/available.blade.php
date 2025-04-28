<x-processor-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Available Batches') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farm</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight (kg)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wool Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shearing Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($batches as $batch)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $batch->batch_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $batch->farm->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $batch->wool_weight }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($batch->wool_type) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $batch->shearing_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex space-x-3">
                                                <a href="{{ route('processor.batches.show', $batch) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                <form action="{{ route('processor.batches.start-processing', $batch) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900">Start Processing</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No batches available for processing.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $batches->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-processor-app-layout> 