<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- Vite resources (CSS and JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body>
<header>
    @if(auth()->check())
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
                <div class="relative flex h-16 items-center justify-between">
                    <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                        <!-- Mobile menu button-->
                        <button type="button"
                                class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                aria-controls="mobile-menu" aria-expanded="false">
                            <span class="absolute -inset-0.5"></span>
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                            </svg>
                            <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                        <div class="flex flex-shrink-0 items-center">
                            <img class="h-8 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
                        </div>
                        <div class="hidden sm:ml-6 sm:block">
                            <div class="flex space-x-4">
                                <a href="{{ route('admin.dashboard') }}" class="rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white" aria-current="page">Dashboard</a>
                                <a href="{{ route('products.index') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Products</a>
{{--                                <a href="{{ route('categories.index') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Categories</a>--}}
                                <a href="{{ route('attributes.index') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Attributes</a>
                                <a href="{{ route('catalogs.index') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Catalogs</a>
                            </div>
                        </div>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                        <!-- Profile dropdown -->
                        <div class="relative ml-3">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white" type="submit">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sm:hidden" id="mobile-menu">
                <div class="space-y-1 px-2 pb-3 pt-2">
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white" aria-current="page">Dashboard</a>
                    <a href="{{ route('products.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Products</a>
{{--                    <a href="{{ route('categories.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Categories</a>--}}
                    <a href="{{ route('attributes.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Attributes</a>
                    <a href="{{ route('catalogs.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Catalogs</a>
                </div>
            </div>
        </nav>
    @endif
</header>

<main>
    @yield('content') <!-- This is where the specific view content will go -->
</main>

<footer>
    <!-- Footer content -->
</footer>

<script src="{{ asset('js/app.js') }}"></script>
<!-- Livewire Scripts -->
@livewireScripts

<!-- Optional: Include any additional JS (should go before Livewire) -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
