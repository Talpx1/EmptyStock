<?php

use App\Models\User;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;

test('user page is displayed', function () {
    $user = User::factory()->withActiveProfile()->create();

    actingAs($user);

    $response = get('/user');

    $response
        ->assertOk()
        ->assertSeeVolt('user.update-user-information-form')
        ->assertSeeVolt('user.update-password-form')
        ->assertSeeVolt('user.delete-user-form');
});

test('user information can be updated', function () {
    $user = User::factory()->create();

    actingAs($user);

    $component = Volt::test('user.update-user-information-form')
        ->set('first_name', 'Test')
        ->set('last_name', 'User')
        ->set('email', 'test@example.com')
        ->call('updateUserInformation');

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $user->refresh();

    assertSame('Test', $user->first_name);
    assertSame('User', $user->last_name);
    assertSame('test@example.com', $user->email);
    assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    actingAs($user);

    $component = Volt::test('user.update-user-information-form')
        ->set('first_name', 'Test')
        ->set('last_name', 'User')
        ->set('email', $user->email)
        ->call('updateUserInformation');

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    actingAs($user);

    $component = Volt::test('user.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/');

    assertGuest();
    assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    actingAs($user);

    $component = Volt::test('user.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $component
        ->assertHasErrors('password')
        ->assertNoRedirect();

    assertNotNull($user->fresh());
});
