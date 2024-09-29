<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;

test('profile page is displayed', function () {
    $user = User::factory()->withActiveProfile()->create();

    actingAs($user)->get(route('profile'))
        ->assertOk()
        ->assertSeeVolt('profile.update-profile-information-form')
        ->assertSeeVolt('profile.delete-profile-form');
});

test('profile information can be updated', function () {
    $user = User::factory()->withActiveProfile()->create();

    actingAs($user);

    $component = Volt::test('profile.update-profile-information-form')
        ->set('username', 'test123')
        ->call('updateProfileInformation');

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    expect($user->active_profile->fresh()->username)->toBe('test123');
});

test('user cannot delete his only profile', function () {
    $user = User::factory()->withActiveProfile()->create();

    actingAs($user);

    Volt::test('profile.delete-profile-form')
        ->set('password', 'password')
        ->call('deleteProfile')
        ->assertHasNoErrors()
        ->assertNoRedirect();

    assertModelExists($user->active_profile);
});

test('user can delete one of his profiles', function () {
    $user = User::factory()->withActiveProfile()->create();
    $to_be_deleted_profile = $user->active_profile;
    $alt_profile = Profile::factory()->for($user)->create();

    actingAs($user);

    Volt::test('profile.delete-profile-form')
        ->set('password', 'password')
        ->call('deleteProfile')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    assertModelMissing($to_be_deleted_profile);
    expect($user->fresh()->active_profile)->toBe($alt_profile);
});

test('correct password must be provided to delete profile', function () {
    $user = User::factory()->withActiveProfile()->create();
    Profile::factory()->for($user)->create();

    actingAs($user);

    Volt::test('profile.delete-profile-form')
        ->set('password', 'wrong-password')
        ->call('deleteProfile')
        ->assertHasErrors('password')
        ->assertNoRedirect();

    assertModelExists($user->active_profile);
});

describe('validation', function () {
    test('username is required', function () {
        actingAs(User::factory()->withActiveProfile()->create());

        Volt::test('profile.update-profile-information-form')
            ->set('username', '')
            ->call('updateProfileInformation')
            ->assertHasErrors(['username' => ['required']]);
    });

    test('username must be string', function () {
        actingAs(User::factory()->withActiveProfile()->create());

        Volt::test('profile.update-profile-information-form')
            ->set('username', 123)
            ->call('updateProfileInformation')
            ->assertHasErrors(['username' => ['string']]);
    });

    test('username must be at least 3 chars long', function () {
        actingAs(User::factory()->withActiveProfile()->create());

        Volt::test('profile.update-profile-information-form')
            ->set('username', 'ab')
            ->call('updateProfileInformation')
            ->assertHasErrors(['username' => ['min:3']]);
    });

    test('username must be at most 255 chars long', function () {
        actingAs(User::factory()->withActiveProfile()->create());

        Volt::test('profile.update-profile-information-form')
            ->set('username', Str::random(256))
            ->call('updateProfileInformation')
            ->assertHasErrors(['username' => ['max:255']]);
    });

    test('username must be lowercase', function () {
        actingAs(User::factory()->withActiveProfile()->create());

        Volt::test('profile.update-profile-information-form')
            ->set('username', 'TESTUSERNAME')
            ->call('updateProfileInformation')
            ->assertHasErrors(['username' => ['lowercase']]);
    });

    test('username must be valid', function (string $username) {
        //covers the "new Username" rule -> tested in isolation in Unit/Rules/UsernameTest

        actingAs(User::factory()->withActiveProfile()->create());

        Volt::test('profile.update-profile-information-form')
            ->set('username', $username)
            ->call('updateProfileInformation')
            ->assertHasErrors(['username']);
    })->with('invalid_usernames');
});
