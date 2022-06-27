<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirect unauthenticated users')
    ->expectGuest()->toBeRedirectedFor('/friends');

it('shows a list of the users pending friends', function () {
    $user = User::factory()->create();
    $friend = User::factory()->times(2)->create();

    $friend->each(fn ($friend) => $user->addFriend(($friend)));

    actingAs($user)
        ->get('/friends')
        ->assertOk()
        ->assertSeeTextInOrder(array_merge(['Pending friend requests'], $friend->pluck('name')->toArray()));
});

it('shows a list of the users friends request', function () {
    $user = User::factory()->create();
    $friend = User::factory()->times(2)->create();

    $friend->each(fn ($friend) => $friend->addFriend(($user)));

    actingAs($user)
        ->get('/friends')
        ->assertOk()
        ->assertSeeTextInOrder(array_merge(['Friend requests'], $friend->pluck('name')->toArray()));
});

it('shows a list of users accepted friends', function () {
    $user = User::factory()->create();
    $friend = User::factory()->times(2)->create();

    $friend->each(function ($friend) use ($user) {
        $user->addFriend($friend);
        $friend->acceptFriend($user);
    });

    actingAs($user)
        ->get('/friends')
        ->assertOk()
        ->assertSeeTextInOrder(array_merge(['Friends'], $friend->pluck('name')->toArray()));
});
