<?php

use App\Models\Pivot\BookUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('only allows authenticated users')
    ->expectGuest()->toBeRedirectedFor('/books/create', 'get');

it('shows the available statuses on the form', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/books/create')
        ->assertSeeTextInOrder(BookUser::$statuses);
});
