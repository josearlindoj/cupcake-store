<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- In the <head> section -->
    @livewireStyles
</head>
<body>
<div id="app">
    @yield('content')
</div>

<!-- Before the closing </body> tag -->
@livewireScripts
</body>
</html>
