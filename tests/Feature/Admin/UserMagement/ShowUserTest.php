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
});

test('it should open the modal when the event is dispatched', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    $lwShow = Livewire::test(Admin\Users\Show::class)
                   ->assertSet('user', null)
                   ->assertSet('modal', false);

    Livewire::test(Admin\Users\Index::class)
                   ->call('showUser', $user->id)
                   ->assertDispatched('user::show', userId: $user->id);

    // $lwShow->assertSet('user.id', $user->id)
    //         ->assertSet('modal', true);

});

test('making sure that the method loadUser has the attribute On', function () {

    $livewireClass = new Admin\Users\Show();

    $reflection = new ReflectionClass($livewireClass);

    $attributes = $reflection->getMethod('loadUser')->getAttributes();

    expect($attributes)->toHaveCount(1);

    expect($attributes[0]->getName())->toBe('Livewire\Attributes\On')
        ->and($attributes[0]->getArguments())->toHaveCount(1)
        ->and($attributes[0]->getArguments()[0])->toBe('user::show');

});
