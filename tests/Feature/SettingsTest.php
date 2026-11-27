<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('redirects guests from settings to login', function () {
    $this->get(route('settings.edit'))
        ->assertRedirect(route('login'));
});

it('renders the settings page for authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('settings.edit'))
        ->assertOk()
        ->assertSee('Profile')
        ->assertSee('Password');
});

it('updates the user profile', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('user-profile-information.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ])
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
});

it('resets email verification when the email changes', function () {
    $user = User::factory()->create(['email' => 'old@example.com']);

    $this->actingAs($user)
        ->put(route('user-profile-information.update'), [
            'name' => $user->name,
            'email' => 'new@example.com',
        ]);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

it('updates the user password with the current password', function () {
    $user = User::factory()->create(['password' => 'old-password']);

    $this->actingAs($user)
        ->put(route('user-password.update'), [
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->assertSessionHasNoErrors();

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

it('rejects a password update with the wrong current password', function () {
    $user = User::factory()->create(['password' => 'old-password']);

    $this->actingAs($user)
        ->put(route('user-password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->assertSessionHasErrors('current_password', errorBag: 'updatePassword');

    expect(Hash::check('old-password', $user->fresh()->password))->toBeTrue();
});
