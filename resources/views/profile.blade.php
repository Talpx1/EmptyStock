<x-app-layout>
    <div class="space-y-6">
        <x-card shadow class="max-w-3xl">
            <livewire:profile.update-profile-information-form />
        </x-card>

        <x-card shadow class="max-w-3xl">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('User Information') }}
                </h2>

            </header>

            <x-alert :title="__('Cross-profile modifications')" :description="__(
                'If you proceed to edit your user information, all the changes will be applied to all profiles.',
            )" icon="o-exclamation-triangle" class="alert-warning mt-3" />

            <x-button class="btn-primary w-full sm:w-auto mt-3" :label="__('Edit user information')" link="{{ route('user') }}" />
        </x-card>
    </div>
</x-app-layout>
