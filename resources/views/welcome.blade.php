<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Auth Roles</title>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS via Vite --}}
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gradient-to-tr from-green-200 via-green-600 to-green-300 dark:bg-[#0a0a0a] text-[#1b1b18] font-sans flex flex-col items-center justify-start p-4 sm:p-6">

<main class="w-full max-w-xl mt-16">
    <div class="text-center mb-10">
        <h1 class="text-3xl sm:text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Welcome to the Wool Journey App</h1>
        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mt-3">Track, Manage, and Monitor Wool from Farm to Fabric</p>
    </div>

    @php
        $roles = [
            'admin' => 'Admin',
            'farmer' => 'Farmer',
            'processor' => 'Processor',
            'distributor' => 'Distributor',
            'retailer' => 'Retailer',
        ];
    @endphp

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-8 flex flex-col gap-6 text-center">
        <label for="role" class="text-lg font-semibold text-gray-700 dark:text-gray-100">Choose a Role</label>
        <select id="role" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="" disabled selected>Select a role</option>
            @foreach ($roles as $guard => $label)
                <option value="{{ $guard }}">{{ $label }}</option>
            @endforeach
        </select>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-4">
            <button onclick="handleRedirect('login')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md w-full sm:w-1/2 transition">
                Login
            </button>
            <button onclick="handleRedirect('register')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md w-full sm:w-1/2 transition">
                Register
            </button>
        </div>
    </div>

    {{-- JS to handle redirection --}}
    <script>
    function handleRedirect(action) {
        const role = document.getElementById('role').value;
        if (!role) {
            alert("Please select a role first.");
            return;
        }

        const routes = {
            @foreach ($roles as $guard => $label)
                @if (Route::has("$guard.login"))
                    "{{ $guard }}_login": "{{ route("$guard.login") }}",
                @endif
                @if (Route::has("$guard.register"))
                    "{{ $guard }}_register": "{{ route("$guard.register") }}",
                @endif
            @endforeach
        };

        console.log(routes); // Debug output

        const key = `${role}_${action}`;
        if (routes[key]) {
            window.location.href = routes[key];
        } else {
            alert("The selected action is not available for this role.");
        }
    }
</script>
</main>

</body>
</html>
