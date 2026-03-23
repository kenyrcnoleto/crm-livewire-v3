<?php

use App\Livewire\Admin;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('it should be able to show all the details of the user in component', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    Livewire::test(Admin\Users\Show::class)
        ->call('loadUser', $user->id)
        ->assertSet('user.id', $user->id)
        ->assertSet('modal', true)
        ->assertSee($user->name)
        ->assertSee($user->email)
        ->assertSee($user->created_at->format('d/m/Y H:i'))
        ->assertSee($user->updated_at->format('d/m/Y H:i'))
        ->assertSee($user->deleted_at?->format('d/m/Y H:i') ?? 'N/A')
        ->assertSee($user->createdBy?->name ?? 'N/A')
        ->assertSee($user->deletedBy->name ?? 'N/A');
})->only();
