<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('users with the default password see the update password alert', function () {
    $user = User::factory()->create([
        'password' => Hash::make(User::DEFAULT_PASSWORD),
    ]);

    $this->actingAs($user);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('id="default-password-alert"', false)
        ->assertSee(route('password.edit'), false);
});

test('users with a custom password do not see the update password alert', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('id="default-password-alert"', false);
});

test('the default password alert is shown only once per session', function () {
    $user = User::factory()->create([
        'password' => Hash::make(User::DEFAULT_PASSWORD),
    ]);

    $this->actingAs($user);

    $this->get(route('dashboard'))
        ->assertSee('id="default-password-alert"', false);

    $this->get(route('dashboard'))
        ->assertDontSee('id="default-password-alert"', false);
});

test('the default password alert is hidden on the password update page', function () {
    $user = User::factory()->create([
        'password' => Hash::make(User::DEFAULT_PASSWORD),
    ]);

    $this->actingAs($user);

    $this->get(route('password.edit'))
        ->assertOk()
        ->assertDontSee('id="default-password-alert"', false);
});
