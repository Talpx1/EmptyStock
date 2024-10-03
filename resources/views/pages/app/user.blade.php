{{-- //TODO: refactor to use x-card-stack and app-card --}}
<x-app-layout>
    <div class="space-y-6">
        <x-card shadow class="max-w-3xl">
            <livewire:user.update-user-information-form />
        </x-card>

        <x-card shadow class="max-w-3xl">
            <livewire:user.update-password-form />
        </x-card>

        <x-card shadow class="max-w-3xl">
            <livewire:user.delete-user-form />
        </x-card>
    </div>
</x-app-layout>
