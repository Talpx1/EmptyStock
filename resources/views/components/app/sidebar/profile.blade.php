@props(['profile'])

<x-list-item :item="$profile" no-separator no-hover class="-mx-2 !-my-2 rounded">
    <x-slot:value>
        {{ '@' . $profile->username }}
    </x-slot:value>
    <x-slot:actions>
        <div class="flex gap-4">

            @php($inactive_profiles = auth()->user()->inactive_profiles)
            @if ($inactive_profiles->count() > 0)
                <x-popover>
                    <x-slot:trigger>
                        <x-button icon="m-arrows-right-left" class="btn-circle btn-ghost btn-xs" />
                    </x-slot:trigger>
                    <x-slot:content>
                        <div class="space-y-2 p-2">
                            <div class="text-sm font-bold">{{ __('Switch Profile') }}</div>
                            @forelse ($inactive_profiles as $profile)
                                {{-- //TODO: test to assert that this components are correctly generated --}}
                                <livewire:layout.switch-profile-button :profile="$profile" />
                            @empty
                                <div>{{ __('No other profiles available.') }}</div>
                            @endforelse
                        </div>
                    </x-slot:content>
                </x-popover>
            @endif


            <x-button icon="o-pencil" link="{{ route('app.profile') }}" wire:navigate :tooltip-left="__('Edit Profile')"
                class="btn-circle btn-ghost btn-xs" />

            <livewire:layout.logout-button />
        </div>
    </x-slot:actions>
</x-list-item>
