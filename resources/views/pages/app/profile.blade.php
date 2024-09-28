@php
    use App\Models\User;

    $profile = type(auth()->user())->as(User::class)->active_profile;
@endphp

<x-app.card-stack-page>
    <x-app.app-card>
        <x-slot:heading>{{ __('Profile Information') }}</x-slot:heading>
        <x-slot:description>{{ __('Update profile information for @:username', ['username' => $profile->username]) }}</x-slot:description>
        <livewire:profile.update-profile-information-form />
    </x-app.app-card>

    {{-- TODO: only show this if the profile has a shop associated --}}
    <x-app.app-card>
        <x-slot:heading>{{ __('Turn into Shop Profile') }}</x-slot:heading>
        <x-slot:description>
            {{ __(
                'You may turn this profile (@:username) into a "Shop Profile", meaning that it will be the owner of a new Shop. Alternatively, you can keep this profile as a "Customer Profile" and open a new one for your Shop.',
                ['username' => $profile->username],
            ) }}
        </x-slot:description>

        <x-slot:footer>
            <div class="flex flex-col sm:flex-row gap-6">
                <x-button class="btn-primary w-full sm:w-auto" :label="__('Turn this profile into a Shop Profile')" link="{{ route('user') }}" />
                <x-button class="btn-primary w-full sm:w-auto" :label="__('Create a new Shop Profile')" link="{{ route('user') }}" />
            </div>
        </x-slot:footer>
    </x-app.app-card>

    <x-app.app-card>
        <x-slot:heading>{{ __('User Information') }}</x-slot:heading>

        <x-alert :title="__('Cross-profile modifications')" :description="__('If you proceed to edit your user information, all the changes will be applied to all profiles.')" icon="o-exclamation-triangle" class="alert-warning mt-3" />

        <x-slot:footer>
            <x-button class="btn-primary w-full sm:w-auto" :label="__('Edit user information')" link="{{ route('user') }}" />
        </x-slot:footer>
    </x-app.app-card>

    <x-app.app-card>
        <x-slot:heading>{{ __('Delete Profile') }}</x-slot:heading>
        <x-slot:description>{{ __('Once your profile is deleted, all of its resources and data will be permanently deleted. This action can\'t be undone.') }}</x-slot:description>

        <x-alert :title="__('Your shop may get deleted!')" :description="__(
            'If this profile is the owner of one or more shops, those will be deleted as well. Please transfer ownership in the shop settings if you don\'t want your shop to be deleted.',
        )" icon="o-exclamation-triangle" class="alert-warning mb-6" />

        <livewire:profile.delete-profile-form />
    </x-app.app-card>
</x-app.card-stack-page>
