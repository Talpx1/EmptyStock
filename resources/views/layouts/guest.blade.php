<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200 grid place-content-center justify-items-center">
    <div class="mb-2">
        <a href="/" wire:navigate>
            <x-app-logo class="w-20 h-20 fill-current text-gray-500" />
        </a>
    </div>

    <x-card shadow class="max-w-lg">
        <div class="mb-4 w-fit ml-auto">
            <x-theme-toggle />
        </div>
        {{ $slot }}
    </x-card>
</body>

</html>
