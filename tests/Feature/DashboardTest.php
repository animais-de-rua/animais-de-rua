<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get(route('backpack.dashboard'))->assertRedirect(route('backpack.auth.login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user);
    $this->get(route('backpack.dashboard'))->assertStatus(200);
});
