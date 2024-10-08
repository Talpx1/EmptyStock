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

<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <div class="ml-5 pt-5">{{ config('app.name', 'Laravel') }}</div>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            <div class="ml-5 pt-5 flex flex-col gap-5 items-center">
                {{-- BRAND --}}
                <x-app-logo class="max-w-24" />

                {{-- UTILS --}}
                <div class="flex gap-4">
                    {{-- THEME TOGGLE --}}
                    <x-theme-toggle />
                </div>
            </div>

            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}
                @if ($user = auth()->user())
                    <x-menu-separator />

                    <x-app.sidebar.profile :profile="$user->active_profile" />

                    <x-menu-separator />
                @endif

                <x-app.sidebar.nav :profile="$user->active_profile" />
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                {{ $slot }}
            </div>
        </x-slot:content>
    </x-main>

    {{-- Toast --}}
    <x-toast />
</body>

</html>
