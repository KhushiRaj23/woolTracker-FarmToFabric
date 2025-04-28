<x-distributor-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Your Shipments') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow sm:rounded-lg p-6">
        @if($shipments->isEmpty())
          <p class="text-gray-600">No shipments found.</p>
        @else
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th class="px-4 py-2 text-left text-sm font-medium">ID</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Batch</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Status</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Dispatched</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Delivered</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($shipments as $s)
                <tr>
                  <td class="px-4 py-2">{{ $s->id }}</td>
                  <td class="px-4 py-2">{{ $s->batch_id }}</td>
                  <td class="px-4 py-2 capitalize">{{ $s->status }}</td>
                  <td class="px-4 py-2">{{ optional($s->dispatched_at)->format('Y-m-d') ?? '—' }}</td>
                  <td class="px-4 py-2">{{ optional($s->delivered_at)->format('Y-m-d') ?? '—' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>
    </div>
  </div>
</x-distributor-app-layout>
