<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirect unauthenticated users')
    ->expectGuest()->toBeRedirectedFor('/friends', 'post');

it('validate the email address is required', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post('/friends')
        ->assertSessionHasErrors(['email']);
});

it('validate the email address is exist', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post('/friends', [
            'email' => 'mabel@codecourse.com'
        ])
        ->assertSessionHasErrors(['email']);
});

it('cant add self as friend', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post('/friends', [
            'email' => $user->email,
        ])
        ->assertSessionHasErrors(['email']);
});

it('store the friend request', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();

    actingAs($user)
        ->post('/friends', [
            'email' => $friend->email,
        ]);

    $this->assertDatabaseHas('friends', [
        'user_id' => $user->id,
        'friend_id' => $friend->id,
        'accepted' => false
    ]);
});

// validate the email

// cant add self as friend

// stores the friend request
