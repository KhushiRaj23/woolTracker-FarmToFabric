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
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex flex-col items-center min-h-screen p-6 lg:p-8 font-sans">

    <header class="w-full max-w-[335px] lg:max-w-4xl text-sm mb-6">
        @if (Route::has('login'))
            <nav class="flex flex-wrap justify-center lg:justify-end gap-4">
                {{-- Web Auth --}}
                @auth('web')
                    <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-link border">Register</a>
                    @endif
                @endauth

                {{-- Admin Auth --}}
                @auth('admin')
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link">Admin Dashboard</a>
                @else
                    <a href="{{ route('admin.login') }}" class="nav-link">Admin Log in</a>
                    @if (Route::has('admin.register'))
                        <a href="{{ route('admin.register') }}" class="nav-link border">Admin Register</a>
                    @endif
                @endauth

                {{-- Farmer Auth --}}
                @auth('farmer')
                    <a href="{{ url('/farmer/dashboard') }}" class="nav-link">Farmer Dashboard</a>
                @else
                    <a href="{{ route('farmer.login') }}" class="nav-link">Farmer Log in</a>
                    @if (Route::has('farmer.register'))
                        <a href="{{ route('farmer.register') }}" class="nav-link border">Farmer Register</a>
                    @endif
                @endauth

                {{-- Processor Auth --}}
                @auth('processor')
                    <a href="{{ url('/processor/dashboard') }}" class="nav-link">Processor Dashboard</a>
                @else
                    <a href="{{ route('processor.login') }}" class="nav-link">Processor Log in</a>
                    @if (Route::has('processor.register'))
                        <a href="{{ route('processor.register') }}" class="nav-link border">Processor Register</a>
                    @endif
                @endauth

                {{-- Distributor Auth --}}
                @auth('distributor')
                    <a href="{{ url('/distributor/dashboard') }}" class="nav-link">Distributor Dashboard</a>
                @else
                    <a href="{{ route('distributor.login') }}" class="nav-link">Distributor Log in</a>
                    @if (Route::has('distributor.register'))
                        <a href="{{ route('distributor.register') }}" class="nav-link border">Distributor Register</a>
                    @endif
                @endauth

                {{--Retailer Auth --}}
                @auth('retailer')
                    <a href="{{ url('/retailer/dashboard') }}" class="nav-link">Retailer Dashboard</a>
                @else
                    <a href="{{ route('retailer.login') }}" class="nav-link">Retailer Log in</a>
                    @if (Route::has('retailer.register'))
                        <a href="{{ route('retailer.register') }}" class="nav-link border">Retailer Register</a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <main class="text-center mt-12">
        <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Welcome to the Wool Journey App</h1>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">Track, Manage, and Monitor Wool from Farm to Fabric</p>
    </main>

    @if (Route::has('login'))
        <div class="hidden h-[3.625rem] lg:block"></div>
    @endif

</body>
</html>
