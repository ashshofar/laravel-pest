<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->user = User::factory()->create();
});

it('only allows authenticated users to post')
    ->expectGuest()->toBeRedirectedFor('/books', 'post');

it('create a book', function () {
    actingAs($this->user)
        ->post('/books', [
           'title' => 'A book',
           'author' => 'An author',
           'status' => 'WANT_TO_READ'
        ]);

    $this->assertDatabaseHas('books', [
        'title' => 'A book',
        'author' => 'An author',
    ])
    ->assertDatabaseHas('book_user', [
        'user_id' => $this->user->id,
        'status' => 'WANT_TO_READ',
    ]);
});

it('requires a book title, author, and status')
    ->tap(fn () => actingAs($this->user))
    ->post('/books')
    ->assertSessionHasErrors(['title', 'author', 'status']);

it('requires a valid status')
    ->tap(fn () => actingAs(($this->user)))
    ->post('/books', [
        'status' => 'EATING'
    ])
    ->assertSessionHasErrors(['status']);
