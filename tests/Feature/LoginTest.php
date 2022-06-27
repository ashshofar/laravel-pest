<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('show the login page')->get('/auth/login')->assertOk();

it('redirect authenticated user', function () {
    expect(User::factory()->create())->toBeRedirectedFor('/auth/login');
});

it('shows an errors if the details are not provided')
    ->post('/login')
    ->assertSessionHasErrors(['email', 'password']);

it('logs the user in', function() {
    $user = User::factory()->create([
        'password' => bcrypt('meowimacat')
    ]);

    post('/login', [
        'email' => $user->email,
        'password' => 'meowimacat'
    ])
    ->assertRedirect('/');

    $this->assertAuthenticated();
});
