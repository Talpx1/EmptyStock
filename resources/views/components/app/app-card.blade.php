<x-card shadow class="max-w-3xl">
    <div class="space-y-6">
        @if ((isset($heading) && $heading->hasActualContent()) || (isset($description) && $description->hasActualContent()))
            <header>
                @if (isset($heading) && $heading->hasActualContent())
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $heading }}
                    </h2>
                @endif
                @if (isset($description) && $description->hasActualContent())
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ $description }}
                    </p>
                @endif
            </header>
        @endif
        @if ($slot->hasActualContent())
            <div>{{ $slot }}</div>
        @endif
        @if (isset($footer) && $footer->hasActualContent())
            <footer>{{ $footer }}</footer>
        @endif
    </div>
</x-card>
