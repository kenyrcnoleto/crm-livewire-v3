<?php

use App\Livewire\Admin\Dashboard;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

test('it should block access to users without the permission _be and admin_', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(Dashboard::class)
        ->assertForbidden();

    get(route('admin.dashboard'))
        ->assertForbidden();
});
