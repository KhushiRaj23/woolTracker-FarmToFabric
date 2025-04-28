@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-4">
                    <h5 class="text-xl font-semibold mb-2">{{ $batch->name }}</h5>
                    <p class="text-gray-600"><span class="font-medium">Farm:</span> {{ $batch->farm->name }}</p>
                    <p class="text-gray-600"><span class="font-medium">Type:</span> {{ $batch->type }}</p>
                    <p class="text-gray-600"><span class="font-medium">Status:</span> {{ $batch->status }}</p>
                    <p class="text-gray-600"><span class="font-medium">Description:</span> {{ $batch->description }}</p>
                    <p class="text-gray-600"><span class="font-medium">Created At:</span> {{ $batch->created_at->format('Y-m-d H:i:s') }}</p>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('farmer.batches.edit', $batch) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit Batch</a>
                    <a href="{{ route('farmer.farms.show', $batch->farm) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to Farm</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 