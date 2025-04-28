<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                {{ __('Add New User') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- User Type Navigation -->
                    <nav class="flex space-x-4 mb-6 border-b border-gray-200 pb-4">
                        <a href="{{ request()->url() }}?type=all" class="@if(request('type', 'all') === 'all') text-indigo-600 border-b-2 border-indigo-600 @else text-gray-500 hover:text-gray-700 @endif px-3 py-2 text-sm font-medium">
                            All Users
                        </a>
                        <a href="{{ request()->url() }}?type=admin" class="@if(request('type') === 'admin') text-indigo-600 border-b-2 border-indigo-600 @else text-gray-500 hover:text-gray-700 @endif px-3 py-2 text-sm font-medium">
                            Admins
                        </a>
                        <a href="{{ request()->url() }}?type=farmer" class="@if(request('type') === 'farmer') text-indigo-600 border-b-2 border-indigo-600 @else text-gray-500 hover:text-gray-700 @endif px-3 py-2 text-sm font-medium">
                            Farmers
                        </a>
                        <a href="{{ request()->url() }}?type=processor" class="@if(request('type') === 'processor') text-indigo-600 border-b-2 border-indigo-600 @else text-gray-500 hover:text-gray-700 @endif px-3 py-2 text-sm font-medium">
                            Processors
                        </a>
                        <a href="{{ request()->url() }}?type=distributor" class="@if(request('type') === 'distributor') text-indigo-600 border-b-2 border-indigo-600 @else text-gray-500 hover:text-gray-700 @endif px-3 py-2 text-sm font-medium">
                            Distributors
                        </a>
                        <a href="{{ request()->url() }}?type=retailer" class="@if(request('type') === 'retailer') text-indigo-600 border-b-2 border-indigo-600 @else text-gray-500 hover:text-gray-700 @endif px-3 py-2 text-sm font-medium">
                            Retailers
                        </a>
                    </nav>

                    <!-- Users Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <span class="text-lg font-medium text-gray-600">{{ substr($user['name'], 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user['name'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user['email'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($user['role'] === 'admin') bg-purple-100 text-purple-800 
                                                @elseif($user['role'] === 'processor') bg-blue-100 text-blue-800
                                                @elseif($user['role'] === 'distributor') bg-green-100 text-green-800
                                                @elseif($user['role'] === 'retailer') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($user['role']) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($user['status'] === 'Active') bg-green-100 text-green-800 
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $user['status'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user['joined'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            @if($user['model_type'] === 'user')
                                                <a href="{{ route('admin.users.edit', $user['id']) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('admin.users.destroy', $user['id']) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                                </form>
                                            @elseif($user['model_type'] === 'farmer')
                                                <a href="{{ route('admin.farmers.edit', $user['id']) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('admin.farmers.destroy', $user['id']) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Are you sure you want to delete this farmer?')">Delete</button>
                                                </form>
                                            @elseif($user['model_type'] === 'distributor')
                                                <a href="{{ route('admin.distributors.edit', $user['id']) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('admin.distributors.destroy', $user['id']) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Are you sure you want to delete this distributor?')">Delete</button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.retailers.edit', $user['id']) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('admin.retailers.destroy', $user['id']) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Are you sure you want to delete this retailer?')">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 